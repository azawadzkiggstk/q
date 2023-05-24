<?php

declare(strict_types=1);

namespace Statik\Blocks\Utils\Acf;

/**
 * AcfLocation class.
 */
class AcfLocation
{
    protected array $rules = [];
    protected array $unsetRules = [];

    /**
     * AcfLocation constructor.
     */
    public function __construct(protected array $fieldGroups = [], protected array $postTypes = [])
    {
        $this->fieldGroups = $this->fieldGroups ?: \acf_get_field_groups();
        $this->postTypes = $this->postTypes ?: \get_post_types();
    }

    /**
     * Set rule for CPT.
     */
    public function setRule(string $groupName, string $cpt): void
    {
        $this->rules[$cpt] = \array_unique(\array_merge($this->rules[$cpt] ?? [], [$groupName]));
    }

    /**
     * Unset rule for CPT.
     */
    public function unsetRule(string $groupName, string $cpt): void
    {
        $this->unsetRules[$cpt] = \array_unique(\array_merge($this->unsetRules[$cpt] ?? [], [$groupName]));
    }

    /**
     * Get the rules.
     */
    public function getRules(): array
    {
        if (empty($this->rules)) {
            return [];
        }

        if (empty($this->unsetRules)) {
            return $this->rules;
        }

        foreach ($this->unsetRules as $field_group => $types) {
            if (false === isset($this->rules[$field_group])) {
                return $this->rules;
            }

            if (empty($types) || false === \is_array($types)) {
                return $this->rules;
            }

            foreach ($types as $type) {
                if (($key = \array_search($type, $this->rules[$field_group])) !== false) {
                    unset($this->rules[$field_group][$key]);
                }
            }
        }

        return $this->rules;
    }

    /**
     * Determinate location rules.
     */
    public function determineLocationRules(): void
    {
        foreach ($this->fieldGroups as $group) {
            if (empty($group['location']) || false === \is_array($group['location'])) {
                continue;
            }

            foreach (\array_filter($group['location']) as $location) {
                foreach ($location as $rule) {
                    $operator = $rule['operator'] ?? '==';
                    $param = $rule['param'] ?? null;
                    $value = $rule['value'] ?? null;

                    if (empty($param) || empty($value)) {
                        continue;
                    }

                    $this->determineRules($group['key'], $param, $operator, $value);
                }
            }
        }
    }

    /**
     * Determine rules.
     */
    private function determineRules(string $groupName, string $param, string $operator, string $value): void
    {
        switch ($param) {
            case 'post_type':
                $this->determinePostTypeRules($groupName, $operator, $value);
                break;
            case 'post_template':
            case 'page_template':
                $this->determinePostTemplateRules($groupName, $operator, $value);
                break;
            case 'post_status':
                foreach ($this->postTypes as $postType) {
                    $this->setRule($groupName, $postType);
                }
                break;
            case 'post_format':
            case 'post_category':
            case 'post_taxonomy':
                $this->setRule($groupName, 'post');
                break;
            case 'post':
                $this->determinePostRules($groupName, $operator, $value);
                break;
            case 'page_type':
                $this->determinePageTypeRules($groupName, $value);
                break;
            case 'page_parent':
            case 'page':
                $this->setRule($groupName, 'page');
                break;
            default:
                break;
        }
    }

    /**
     * Determines how the Post Type rule should be displayed.
     */
    private function determinePostTypeRules(string $groupName, string $operator, string $value): void
    {
        if ('==' === $operator) {
            if ('all' === $value) {
                foreach ($this->postTypes as $postType) {
                    $this->setRule($groupName, $postType);
                }
            } else {
                if (\in_array($value, $this->postTypes, true)) {
                    $this->setRule($groupName, $value);
                }
            }
        }

        if ('!=' === $operator) {
            if ('all' !== $value) {
                foreach ($this->postTypes as $postType) {
                    $this->setRule($groupName, $postType);
                }
            }

            $this->unsetRule($groupName, $value);
        }
    }

    /**
     * Determines how the Post Template rule should be displayed.
     */
    private function determinePostTemplateRules(string $groupName, string $operator, string $value): void
    {
        $templates = \wp_get_theme()->get_post_templates();

        if ('==' === $operator && isset($templates[$value])) {
            $this->setRule($groupName, $templates[$value]);
        }

        if ('!=' !== $operator) {
            return;
        }

        foreach ($templates as $template) {
            $this->setRule($groupName, $template);
        }

        if (isset($templates[$value])) {
            $this->unsetRule($groupName, $templates[$value]);
        }
    }

    /**
     * Determines how the Posts rule should be displayed.
     */
    private function determinePostRules(string $groupName, string $operator, string $value): void
    {
        if ('==' === $operator && $value) {
            $post = \get_post((int) $value);

            if ($post instanceof \WP_Post) {
                $this->setRule($groupName, $post->post_type);
            }
        }

        if ('!=' !== $operator) {
            return;
        }

        foreach ($this->postTypes as $postType) {
            $this->setRule($groupName, $postType);
        }
    }

    /**
     * Determines how the Page Type rule should be displayed.
     */
    private function determinePageTypeRules(string $groupName, string $value): void
    {
        if (\in_array($value, ['front_page', 'posts_page'], true)) {
            $this->setRule($groupName, 'page');
        }

        if (false === \in_array($value, ['top_level', 'parent', 'child'], true)) {
            return;
        }

        foreach ($this->postTypes as $postType) {
            if ($postType->hierarchical) {
                $this->setRule($groupName, $postType);
            }
        }
    }
}
