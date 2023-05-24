<?php

declare(strict_types=1);

namespace Statik\Deploy\Deployment\Provider\Statik;

use Statik\Deploy\Deployment\Provider\Settings\AbstractSettings;

/**
 * Class Settings.
 */
class Settings extends AbstractSettings
{
    /**
     * {@inheritdoc}
     */
    public function fields(): array
    {
        return [
            'statik.break' => [
                'type' => 'break',
            ],
            'statik.header' => [
                'type' => 'heading',
                'label' => \__('Statik provider settings', 'statik-deployment'),
                'description' => \__('Settings required to fill in to make provider work.', 'statik-deployment'),
            ],
            'statik.api_url' => [
                'type' => 'input',
                'label' => \__('API hook', 'statik-deployment'),
                'description' => \__(
                    'The URL of the API endpoint that can be used to manage deployments.',
                    'statik-deployment'
                ),
                'attrs' => [
                    'class' => 'regular-text',
                    'type' => 'text',
                    'required' => 'required',
                    'autocomplete' => 'off',
                ],
            ],
            'statik.api_token' => [
                'type' => 'input',
                'label' => \__('API token', 'statik-deployment'),
                'description' => \__('Token used to authorize API calls.', 'statik-deployment'),
                'attrs' => [
                    'class' => 'regular-text',
                    'required' => 'required',
                    'type' => 'password',
                    'autocomplete' => 'off',
                ],
            ],
            'statik.site_prefix' => [
                'type' => 'input',
                'label' => \__('Site path prefix', 'statik-deployment'),
                'description' => \__(
                    'This prefix is used during the build in case of using a multisite instance. Mostly, the value'
                    . ' is equal to WP multisite blog path.',
                    'statik-deployment'
                ),
                'value' => [$this, 'getSitePrefix'],
                'attrs' => [
                    'class' => 'regular-text',
                    'type' => 'text',
                    'required' => 'required',
                    'disabled' => 'disabled',
                    'autocomplete' => 'off',
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function statusSteps(): array
    {
        return [
            'SCHEDULED' => [
                'spacer' => true,
                'type' => 'deployment',
                'name' => 'Schedule',
                'label' => \__('Schedule deployment to start', 'statik-deployment'),
                'tooltip' => \__(
                    'Deployment is queued up for launch. This can take up to several minutes, depending on the server'
                    . ' load.',
                    'statik-deployment'
                ),
            ],
            'ANALYZING' => [
                'type' => 'deployment',
                'name' => 'Code analyze',
                'label' => \__('Pull and analyze the source code', 'statik-deployment'),
                'tooltip' => \__(
                    'The source code of the application is downloaded from the repository and analysed to see if it '
                    . 'can be run.',
                    'statik-deployment'
                ),
            ],
            'YARN_INSTALLING' => [
                'type' => 'deployment',
                'name' => 'Install dependencies',
                'label' => \__('Install external Node dependencies', 'statik-deployment'),
                'tooltip' => \__(
                    'External Node dependencies are installed. This action is performed during the initial deployment '
                    . 'and with every change to the source code.',
                    'statik-deployment'
                ),
            ],
            'BUILDING' => [
                'type' => 'deployment',
                'name' => 'Build application',
                'label' => \__('Build static Gatsby application', 'statik-deployment'),
                'tooltip' => \__(
                    'The Gatsby application is built. This is the most time-consuming deployment process during '
                    . 'which, data is pulled from the WordPress instance and static pages are generated.',
                    'statik-deployment'
                ),
            ],
            'DEPLOYING' => [
                'type' => 'deployment',
                'name' => 'Deploy to storage',
                'label' => \__('Deploy to storage bucket', 'statik-deployment'),
                'tooltip' => \__(
                    'The generated static Gatsby page is transferred to the destination bucket and made public.',
                    'statik-deployment'
                ),
            ],
            'READY' => [
                'type' => 'deployment',
                'label' => \__('Finish up the deployment process and make it live', 'statik-deployment'),
            ],
        ];
    }

    /**
     * Get the site prefix.
     */
    public function getSitePrefix(): string
    {
        $path = \is_multisite() && \function_exists('get_blog_details')
            ? \get_blog_details()->path ?? '/'
            : '/';

        /**
         * Fire custom site prefix filters.
         *
         * @param string $path path for current site
         *
         * @return string
         */
        return (string) \apply_filters('Statik\Deploy\customSitePrefix', $path);
    }
}
