import { useStaticQuery, graphql } from 'gatsby';
import { get } from 'lodash';
import axios from 'axios';
import { useCallback } from 'react';

export const useSearch = (searchPhrase) => {
  const { site } = useStaticQuery(graphql`
    query {
      site {
        siteMetadata {
          satellites {
            search {
              host
              token
              prefix
            }
          }
        }
      }
    }
  `);

  const satellites = get(site, 'siteMetadata.satellites.search', {
    host: null,
    token: null,
    prefix: null,
  });

  const fetchSearchResults = useCallback(
    async (searchPhrase) => {
      try {
        if (searchPhrase.length < 3) {
          return;
        }

        const response = await axios({
          method: 'get',
          baseURL: satellites.host,
          headers: { authorization: `Bearer ${satellites.token}` },
          params: { prefix: satellites.prefix, query: searchPhrase },
        });

        return get(response, 'data.hits', []);
      } catch (error) {
        console.error(error);
        return [];
      }
    },
    [satellites],
  );

  return { fetchSearchResults: () => fetchSearchResults(searchPhrase) };
};
