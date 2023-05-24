<?php

declare(strict_types=1);

namespace Statik\Blocks\BlockType;

use Statik\Blocks\Block\Block;
use Statik\Blocks\BlockType\Exception\BlockJsonException;
use Statik\Blocks\DI;
use Statik\Blocks\Utils\Path;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Class AbstractBlock.
 */
abstract class AbstractBlockType implements BlockTypeInterface
{
    protected ?string $slug = null;

    protected ?string $path = null;

    protected ?string $url = null;

    private array $config;

    private ?\WP_Block_Type $wpBlockType = null;

    private ?Block $block = null;

    /**
     * AbstractBlock constructor.
     *
     * @throws BlockJsonException
     */
    public function __construct()
    {
        $block = $this->getPath('block.json');

        if (false === \file_exists($block)) {
            throw new BlockJsonException(\__("Missing 'blocks.json' file", 'statik-blocks'));
        }

        $config = \file_get_contents($block);
        $config = \json_decode($config, true);

        if (\JSON_ERROR_NONE !== \json_last_error()) {
            throw new BlockJsonException(\__("Invalid 'block.json' file", 'statik-blocks'));
        }

        $this->config = $config;

        \add_action('init', fn () => $this->initializeBlockType(), 100);
    }

    /**
     * Initialize block type.
     */
    private function initializeBlockType(): void
    {
        if ($this->wpBlockType) {
            return;
        }

        if (false === \in_array($this, DI::BlocksManager()->getBlockTypesRegistry())) {
            return;
        }

        if ($this instanceof DependencyInterface && false === $this->haveDependencies()) {
            return;
        }

        if ($this instanceof EditorInlineDataInterface) {
            $inlineScripts['extra'] = \array_map(
                fn ($el): mixed => \is_callable($el) ? \call_user_func($el, $this) : $el,
                $this->getEditorInlineData()
            );
        }

        if ($this instanceof SettingsInterface) {
            $inlineScripts['settings'] = $this->getSettings();
        }

        if (isset($inlineScripts)) {
            \add_action(
                'admin_enqueue_scripts',
                fn () => \wp_localize_script(
                    \generate_block_asset_handle($this->getConfig('name'), 'editorScript'),
                    "statikBlocks = window.statikBlocks || {}; statikBlocks['{$this->getConfig('name')}']",
                    $inlineScripts
                )
            );
        }

        if ($this instanceof DynamicBlockInterface) {
            $attributes['render_callback'] = fn (...$args): string => $this->renderBlock(...$args);
        }

        if ($this instanceof InlineDataInterface) {
            \add_filter(
                "render_block_{$this->getConfig('name')}",
                fn (...$args) => $this->enqueueInlineData(...$args),
                10,
                3
            );
        }

        if ($this instanceof OnInitInterface) {
            $this->onInit();
        }

        $this->enqueueEditorStyles();

        $this->wpBlockType = \register_block_type_from_metadata($this->getPath(), $attributes ?? []) ?: null;
    }

    /**
     * Enqueue extra editor styles.
     */
    private function enqueueEditorStyles(): void
    {
        $themeRelativePath = Path::relativePath(\get_template_directory(), $this->getPath());

        foreach (['style', 'editorStyle'] as $style) {
            $config = $this->getConfig($style);

            if (null === $config) {
                continue;
            }

            foreach ((array) $config as $file) {
                $path = \remove_block_asset_path_prefix($file);
                \add_editor_style("{$themeRelativePath}/{$path}");
            }
        }
    }

    /**
     * Render block front view.
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function renderBlock(array $data, string $content, \WP_Block $block): string
    {
        global $post;

        $this->block = new Block(
            $block->parsed_block,
            0,
            null,
            \array_merge($block->context, ['post' => $post instanceof \WP_Post ? $post->to_array() : null])
        );

        $twig = new Environment(new FilesystemLoader($this->getPath('/views')), ['cache' => DI::dir('cache')]);

        if (DI::development()) {
            $twig->enableDebug();
            $twig->enableAutoReload();
            $twig->enableStrictVariables();
            $twig->addExtension(new DebugExtension());
        }

        $renderedBlock = $twig->render(
            'block.twig',
            ['block' => $this->block, 'blockType' => $this, 'innerContent' => $content]
        );

        $this->block = null;

        return $renderedBlock;
    }

    /**
     * Enqueue frontend inline data.
     */
    private function enqueueInlineData(string $content, array $blockData, \WP_Block $block): string
    {
        global $post;

        $this->block = new Block(
            $block->parsed_block,
            0,
            null,
            \array_merge($block->context, ['post' => $post instanceof \WP_Post ? $post->to_array() : null])
        );

        $blockName = \str_replace('-', '', \ucwords($this->getSlug(), '-'));

        /** @var InlineDataInterface $this */
        $data = \array_map(
            fn ($el): mixed => \is_callable($el) ? \call_user_func($el, $this->block) : $el,
            $this->getInlineData()
        );

        if (false === empty($block->block_type->view_script)) {
            \wp_enqueue_script($block->block_type->view_script);
        }

        \wp_add_inline_script(
            \generate_block_asset_handle(
                $this->getConfig('name'),
                $block->block_type->view_script ? 'viewScript' : 'script'
            ),
            \sprintf("typeof %sBlock === 'function' && %sBlock(%s)", $blockName, $blockName, \wp_json_encode($data)),
        );

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlock(): ?Block
    {
        return $this->block;
    }

    /**
     * {@inheritdoc}
     */
    public function getWpBlockType(): ?\WP_Block_Type
    {
        return $this->wpBlockType;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig(string $key = null, mixed $default = null): mixed
    {
        return $key ? $this->config[$key] ?? $default : $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(string $subPath = null): string
    {
        if (null === $this->path) {
            $reflectionClass = new \ReflectionClass($this);
            $path = \dirname($reflectionClass->getFilename());
            $this->path = \wp_normalize_path(\untrailingslashit($path));
        }

        return $this->path . ($subPath ? (\str_starts_with($subPath, '/') ? $subPath : "/{$subPath}") : null);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl(string $subPath = null): string
    {
        $this->url ??= \str_replace(DI::dir(), DI::url(), $this->getPath($subPath));

        return $this->url;
    }

    /**
     * {@inheritDoc}
     */
    public function getSlug(): string
    {
        $this->slug ??= \str_replace('/', '-', $this->getConfig('name'));

        return $this->slug;
    }

    /**
     * Get settings fields structure.
     */
    public function getSettingsSchema(): array
    {
        if (false === $this instanceof SettingsInterface) {
            return [];
        }

        $blockSettings = [
            "{$this->getSlug()}.title" => [
                'type' => 'heading',
                'label' => \sprintf('%s %s', $this->getConfig('title'), \__('settings', 'statik-blocks')),
                'description' => $this->getConfig('description'),
            ],
        ];

        /** @var SettingsInterface|self $this */
        foreach ($this->getSettingsFields() as $key => $setting) {
            $blockSettings["{$this->getSlug()}.{$key}"] = $setting;
        }

        $blockSettings["{$this->getSlug()}.break"] = ['type' => 'break'];

        return $blockSettings;
    }

    /**
     * Get settings values.
     */
    public function getSettings(string $key = null, mixed $default = null): mixed
    {
        if (false === $this instanceof SettingsInterface) {
            return $default;
        }

        if ($key) {
            return DI::Config()->get("{$this->getSlug()}.{$key}.value", $default);
        }

        /** @var SettingsInterface|self $this */
        foreach (\array_keys($this->getSettingsFields()) as $key) {
            $blockSettings[$key] = DI::Config()->get("{$this->getSlug()}.{$key}.value");
        }

        return $blockSettings ?? [];
    }
}
