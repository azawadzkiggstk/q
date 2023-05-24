/**
 * Gatsby Statik utils
 */
require('@statik-space/gatsby-theme-statik/gatsby-utils')();

module.exports = {
  pathPrefix: process.env.GATSBY_BASE_PATH || '/',
  flags: {
    DEV_SSR: true,
  },
  plugins: [
    {
      resolve: '@statik-space/gatsby-theme-statik',
      options: {
        seo: {
          robots: {
            sitemap: '/sitemap.xml',
          },
        },
        sass: {
          resolveGlobalModules: (modules) => [...modules, 'sass:math'],
          resolveGlobalVariables: (variables) => ({
            ...variables,
            // 'customVariable': 'value'
          }),
          resolveGlobalImports: (paths) => [
            ...paths,
            '@commons/globals.scss',
            // path.resolve(__dirname, 'src/assets/stylesheets/my-globals.scss'),
          ],
        },
        wordpress: {
          /**
           * GraphQL endpoint (path), relative to your WordPress home URL.
           * Provide string value, without leading and trailing slash.
           */
          graphqlEndpoint: 'graphql',
          /**
           * Allows you to generate extra useful resources.
           *
           * It will generate:
           *
           * `./gatsby-types.d.ts`
           * TypeScript Type Definitions related with graphql schema.
           * Useful if you want to write type-safe code.
           * Example usage:
           * // .tsconfig.json
           * {
           *     "include": [
           *        "./gatsby-types.d.ts",
           *     ]
           * }
           *
           * `./gatsby-introspection.json`
           * GraphQL JSON schema representation.
           * It may be helpful if you want to use `eslint-plugin-graphql`.
           * Example usage:
           * // .eslintrc
           * {
           *  plugins: ['graphql'],
           *  rules: {
           *    'graphql/template-strings': ['error', {
           *      env: 'relay',
           *      tagName: 'graphql',
           *      schemaJsonFilepath: './gatsby-introspection.json',
           *    }],
           *  },
           * }
           *
           * `./gatsby-schema.graphql`
           * GraphQL SDL (schema definition language) schema representation.
           * It may be helpful if you want to use `ts-graphql-plugin`.
           * Example usage:
           * // .tsconfig.json
           * {
           *    "plugins": [{
           *        "name": "ts-graphql-plugin",
           *        "schema": "gatsby-schema.graphql",
           *        "tag": "graphql"
           *    }]
           * }
           */
          emitGraphqlResources: false,
          searchReplace: {
            resolveTargets: () => [],
            resolvePatterns: (patterns) => patterns,
          },
        },
        ___experiments: {
          fixCollectionRoutes: true,
          fixCSSonDEV_SSR: false,
        },
      },
    },
    {
      resolve: '@statik-space/gatsby-blocks-statik',
      options: {
        customBlocksPath: './src/blocks',
        customBlocksStylesPath: '../commons/assets/stylesheets/blocks',
      },
    },
    // 'gatsby-plugin-webpack-bundle-analyser-v2',
  ].filter((plugin) => plugin.enabled !== false),
};
