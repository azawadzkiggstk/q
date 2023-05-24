import React from 'react';
import { FacebookIcon } from '../../../assets/icons/FacebookIcon';
import { TwitterIcon } from '../../../assets/icons/TwitterIcon';
import { LinkedinIcon } from '../../../assets/icons/LinkedInIcon';
import { LinkIcon } from '../../../assets/icons/LinkIcon';
import { EmailIcon } from '../../../assets/icons/EmailIcon';
import { safeAttrValue } from 'xss';

export const ShareBox = (props) => {
  const { url } = props;

  const copyToClipboard = (event) => {
    event.preventDefault();
    navigator.clipboard.writeText(url);
  };

  return (
    <div className="share-box">
      <p className="share-box__title">Share:</p>
      <ul className="share-box__list">
        <li className="share-box__item">
          <a
            className="share-box__link"
            href={`https://www.facebook.com/sharer/sharer.php?u=${url}`}
            target="_blank"
            rel="noreferrer"
          >
            <FacebookIcon alt="Share on Facebook" role="img" />
          </a>
        </li>
        <li className="share-box__item">
          <a
            href={`https://www.twitter.com/share?url=${url}`}
            target="_blank"
            rel="noreferrer"
            className="share-box__link"
          >
            <TwitterIcon
              className="share-box__icon"
              alt="Share on Twitter"
              role="img"
            />
          </a>
        </li>
        <li className="share-box__item">
          <a
            href={`https://www.linkedin.com/sharing/share-offsite/?url=${url}`}
            target="_blank"
            rel="noreferrer"
            className="share-box__link"
          >
            <LinkedinIcon
              className="share-box__icon"
              alt="Share on LinkedIn"
              role="img"
            />
          </a>
        </li>
        <li className="share-box__item">
          <a
            className="share-box__link"
            href={safeAttrValue('a', 'href', url)}
            onClick={copyToClipboard}
          >
            <LinkIcon
              className="share-box__icon"
              alt="Link to this insight"
              role="img"
            />
          </a>
        </li>
        <li className="share-box__item">
          <a className="share-box__link" href={`mailto:?body=${url}`}>
            <EmailIcon
              className="share-box__icon"
              alt="Share using e-mail client"
              role="img"
            />
          </a>
        </li>
      </ul>
    </div>
  );
};
