import { graphql, useStaticQuery } from 'gatsby';

export const useThemeSettings = () => {
  const { allStatikSettings } = useStaticQuery(
    graphql`
      query StatikThemeSettings {
        allStatikSettings {
          globalSettings {
            primaryLogo {
              ...MediaItem
            }
            secondaryLogo {
              ...MediaItem
            }
          }
          footerSettings {
            footerImprint
            footerParagraph
            statikFooter
            socialMedia {
              facebook
              instagram
              linkedin
              twitter
            }
          }
        }
      }
    `,
  );

  return {
    ...allStatikSettings.footerSettings,
    ...allStatikSettings.globalSettings,
  };
};
