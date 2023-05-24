const path = require('path');

exports.ArchiveCreator = class {
  constructor(graphql, reporter, createPage, componentPath) {
    this.graphql = graphql;
    this.reporter = reporter;
    this.createPage = createPage;
    this.componentPath = componentPath;
  }

  async create(basePath, queryField, queryArgs, pageContext = {}) {
    const entitiesChunks = await this.fetchEntitiesChunks(
      queryField,
      queryArgs,
    );
    this.createArchivePages(basePath, entitiesChunks, pageContext);
  }

  async fetchEntitiesChunks(queryField, queryArgs, queryCursorArg = '') {
    const { graphql, reporter } = this;
    const args = [queryArgs, queryCursorArg].join(',');

    const result = await graphql(`
      query EntitiesQuery {
        ${queryField}(${args}) {
          pageInfo {
            endCursor
            hasNextPage
          }
          nodes {
            databaseId
          }
        }
      }
    `);

    if (result.errors) {
      reporter.panicOnBuild('Error while running GraphQL query.');
      return;
    }

    const { pageInfo, nodes } = result.data[queryField];

    if (pageInfo.hasNextPage) {
      const nextEntities = await this.fetchEntitiesChunks(
        queryField,
        queryArgs,
        `after: "${pageInfo.endCursor}"`,
      );
      return [nodes, ...nextEntities];
    }
    return [nodes];
  }

  createArchivePages(basePath, entitiesChunks, pageContext) {
    const { createPage, componentPath } = this;
    const first = 1;
    const total = entitiesChunks.length;

    entitiesChunks.forEach((chunk, i) => {
      const current = i + 1;
      const prev = current - 1;
      const next = current + 1;

      const { join } = path.posix;

      const firstPath = join('/', basePath, String(first));
      const lastPath = join('/', basePath);
      const currentPath =
        current === total ? lastPath : join('/', basePath, String(current));
      const prevPath =
        current > first
          ? join('/', basePath, String(Math.max(prev, first)))
          : null;
      const nextPath =
        next === total
          ? lastPath
          : current < total
          ? join('/', basePath, String(Math.min(next, total)))
          : null;

      const ids = chunk.map((post) => post.databaseId);

      createPage({
        path: currentPath,
        component: componentPath,
        context: {
          basePath: basePath,
          prevPath: prevPath,
          nextPath: nextPath,
          firstPath: firstPath,
          lastPath: lastPath,
          current: current,
          total: total,
          ids: ids.length ? ids : '',
          ...pageContext,
        },
      });
    });
  }
};
