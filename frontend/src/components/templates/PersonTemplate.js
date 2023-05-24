import React from 'react';
import rot13 from 'rot-13';
import xss, { safeAttrValue } from 'xss';
import { StatikBlocks, StatikImage } from '@statik-space/gatsby-theme-statik';
import { TwitterIcon } from '../../assets/icons/TwitterIcon';
import { LinkedinIcon } from '../../assets/icons/LinkedInIcon';
import { EmailIcon } from '../../assets/icons/EmailIcon';
import { WebsiteIcon } from '../../assets/icons/WebsiteIcon';

export const PersonTemplate = (props) => {
  const { person } = props;
  const email = person.acf.contactDetails.email;
  const linkedin = person.acf.contactDetails.linkedin;
  const twitter = person.acf.contactDetails.twitter;
  const website = person.acf.contactDetails.website;

  return (
    <article id={`person-${person.databaseId}`} className="person">
      {person.featuredImage && (
        <StatikImage mediaItem={person.featuredImage.node} />
      )}
      <section className="person__main-info">
        <p className="person__name">{person.title}</p>
        <div
          className="person__short-description"
          dangerouslySetInnerHTML={{ __html: xss(person.acf.shortDescription) }}
        />
        <div
          className="person__long-description"
          dangerouslySetInnerHTML={{ __html: xss(person.acf.longDescription) }}
        />
        <ul className="person__contact-details">
          {email && (
            <li className="person__contact-item">
              <a href={`mailto:${rot13(email)}`}>
                <EmailIcon
                  alt="Email"
                  role="img"
                  className="person__social-icon"
                />
              </a>
            </li>
          )}
          {twitter && (
            <li className="person__contact-item">
              <a href={safeAttrValue('a', 'href', twitter)}>
                <TwitterIcon
                  alt="Twitter profile"
                  role="img"
                  className="person__social-icon"
                />
              </a>
            </li>
          )}
          {website && (
            <li className="person__contact-item">
              <a href={safeAttrValue('a', 'href', website)}>
                <WebsiteIcon
                  alt="Website"
                  role="img"
                  className="person__social-icon"
                />
              </a>
            </li>
          )}
          {linkedin && (
            <li className="person__contact-item">
              <a href={safeAttrValue('a', 'href', linkedin)}>
                <LinkedinIcon
                  alt="LinkedIn profile"
                  role="img"
                  className="person__social-icon"
                />
              </a>
            </li>
          )}
        </ul>
      </section>
      <section className="person__content">
        {person.gutenbergBlocks?.nodes && (
          <StatikBlocks blocks={person.gutenbergBlocks.nodes} />
        )}
      </section>
    </article>
  );
};
