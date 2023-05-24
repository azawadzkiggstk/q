import React from 'react';
import { friendlyAttrValue, safeAttrValue } from 'xss';
import { useStatikSiteMetaData } from '@statik-space/gatsby-theme-statik';

const Meta = (props) => {
  const { name, property, content, id } = props;

  if (!content || (!name && !property)) {
    return null;
  }

  return (
    <meta
      {...(name ? { name } : {})}
      {...(property ? { property } : {})}
      content={friendlyAttrValue(content)}
      id={id}
    />
  );
};

export const Seo = (props) => {
  const {
    canonicalUrl,
    title,
    description,
    noarchive,
    nofollow,
    noindex,
    ogTitle,
    ogDescription,
    imageUrl,
    twitterTitle,
    twitterDescription,
    twitterImage,
    children
  } = props;

  const siteMeta = useStatikSiteMetaData();

  const linkCanonical = safeAttrValue(
    'link',
    'href',
    `${siteMeta.siteUrl}${canonicalUrl}`,
  );
  const robotsContent = Object.entries({
    noarchive: noarchive,
    nofollow: nofollow,
    noindex: noindex,
  })
    .map(([key, value]) => (value ? key : null))
    .filter((item) => item)
    .join(', ');

  // tricky!
  Seo.htmlLang = siteMeta.language

  return (
    <>
      <title id="head-title">{title || siteMeta.title}</title>
      <link id="head-canonical" rel="canonical" href={linkCanonical} />
      <meta id="head-docsearch-version" name="docsearch:version" content="2.0" />
      <meta
        id="head-viewport"
        name="viewport"
        content="width=device-width,initial-scale=1,shrink-to-fit=no,viewport-fit=cover"
      />
      <Meta id="head-description" name="description" content={description} />
      <Meta id="head-robots" name="robots" content={robotsContent} />

      <Meta id="head-og-title" property="og:title" content={ogTitle || title} />
      <Meta id="head-og-description" property="og:description" content={ogDescription || description} />
      <Meta id="head-og-image" property="og:image" content={imageUrl} />
      <Meta id="head-og-url" property="og:url" content={linkCanonical} />
      <Meta id="head-og-type" property="og:type" content="website" />
      <Meta id="head-og-locale" property="og:locale" content={siteMeta.language} />
      <Meta id="head-og-site-name" property="og:site_name" content={siteMeta.title} />
      <Meta id="head-twitter-title" name="twitter:title" content={twitterTitle || title} />
      <Meta id="head-twitter-description" name="twitter:description" content={twitterDescription || description}
      />
      <Meta id="head-twitter-image" name="twitter:image" content={twitterImage} />
      <Meta id="head-twitter-card" name="twitter:card" content="summary" />
      {children}
    </>
  );
};
