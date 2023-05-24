import './gatsby-types';

namespace NodeJS {
  interface ProcessEnv {
    readonly GATSBY_BASE_URL: string
    readonly WORDPRESS_API_URL: string
    readonly WORDPRESS_API_URI: string
  }
}
