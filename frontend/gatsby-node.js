const { ArchiveCreator } = require('./lib/archive-creator');
const postsArchiveTemplatePath = require.resolve(
  './src/templates/PostsArchive.js',
);

exports.createPages = async (args) => {
  const { graphql, actions, reporter } = args;
  const { createPage } = actions;

  const entitiesPerPage = 6;

  const archiveCreator = new ArchiveCreator(
    graphql,
    reporter,
    createPage,
    postsArchiveTemplatePath,
  );

  await Promise.all([
    // Insights Archive
    archiveCreator.create(
      '/insights',
      'allPosts',
      `first: ${entitiesPerPage}, where: {orderby: {field: DATE, order: ASC}}`,
      {
        postType: 'post',
      },
    ),

    // Categories Archives
    (async () => {
      const categories = await fetchCategories(graphql, reporter);
      await Promise.all(
        categories.map((category) => {
          return archiveCreator.create(
            category.link,
            'allPosts',
            `first: ${entitiesPerPage}, where: {orderby: {field: DATE, order: ASC}, categoryId: ${category.databaseId} }`,
            {
              postType: 'post',
              category: category.slug,
            },
          );
        }),
      );
    })(),
  ]);
};

const fetchCategories = async (graphql, reporter) => {
  const result = await graphql(`
    query CategoriesQuery {
      allCategories {
        nodes {
          databaseId
          link
          slug
        }
      }
    }
  `);
  if (result.errors) {
    reporter.panicOnBuild('Error while running GraphQL query.');
    return;
  }
  return result.data.allCategories.nodes;
};
