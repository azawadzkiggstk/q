import React from 'react';
import { safeAttrValue } from 'xss';
import { FooterMenu } from '../../menus/FooterMenu';
import { SubFooterMenu } from '../../menus/SubFooterMenu';
import { Logo } from '../logo/Logo';
import { FacebookIcon } from './icons/FacebookIcon';
import { InstagramIcon } from './icons/InstagramIcon';
import { LinkedinIcon } from './icons/LinkedInIcon';
import { TwitterIcon } from './icons/TwitterIcon';
import { useThemeSettings } from '../../../hooks/useThemeSettings';

export const Footer = () => {
  const options = useThemeSettings();

  return (
    <footer className="main-footer">
      <div className="main-footer__container">
        <div className="main-footer__row--top">
          <div className="main-footer__navigation">
            <FooterMenu />
          </div>
          <div className="main-footer__navigation">
            <SubFooterMenu />
          </div>
          <div className="main-footer__text">
            {options.footerParagraph && <p>{options.footerParagraph}</p>}
          </div>
          <div className="main-footer__social-media">
            <ul className="main-footer__social-media-list">
              {options.socialMedia?.twitter && (
                <li className="main-footer__social-media-item">
                  <a
                    href={safeAttrValue(
                      'a',
                      'href',
                      options.socialMedia.twitter,
                    )}
                    rel="noopener noreferrer"
                  >
                    <TwitterIcon />
                  </a>
                </li>
              )}
              {options.socialMedia?.facebook && (
                <li className="main-footer__social-media-item">
                  <a
                    href={safeAttrValue(
                      'a',
                      'href',
                      options.socialMedia.facebook,
                    )}
                    rel="noopener noreferrer"
                  >
                    <FacebookIcon />
                  </a>
                </li>
              )}
              {options.socialMedia?.linkedin && (
                <li className="main-footer__social-media-item">
                  <a
                    href={safeAttrValue(
                      'a',
                      'href',
                      options.socialMedia.linkedin,
                    )}
                    rel="noopener noreferrer"
                  >
                    <LinkedinIcon />
                  </a>
                </li>
              )}
              {options.socialMedia?.linkedin && (
                <li className="main-footer__social-media-item">
                  <a
                    href={safeAttrValue(
                      'a',
                      'href',
                      options.socialMedia.linkedin,
                    )}
                    rel="noopener noreferrer"
                  >
                    <InstagramIcon />
                  </a>
                </li>
              )}
            </ul>
          </div>
        </div>
        <div className="main-footer__row--bottom">
          <div className="main-footer__branding">
            <Logo mediaItem={options.secondaryLogo} />
          </div>
          <div className="main-footer__copyright">
            {options.footerImprint && <p>{options.footerImprint}</p>}
          </div>
        </div>
      </div>
    </footer>
  );
};
