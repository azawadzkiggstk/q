import { useStaticQuery, graphql } from 'gatsby';

export const useAllCategories = () => {
  const { allCategories } = useStaticQuery(
    graphql`
      query StatikCategories {
        allCategories {
          nodes {
            uri
            name
            slug
          }
        }
      }
    `,
  );

  return allCategories.nodes;
};
