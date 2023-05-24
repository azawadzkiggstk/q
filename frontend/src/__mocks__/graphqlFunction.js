exports.schemaQueryQraphql = jest.fn().mockImplementation(() => {
  return {
    data: {
      __schema: {
        queryType: {
          fields: [
            {
              name: 'block',
            },
            {
              name: 'allBlock',
            },
            {
              name: 'wordpressWpSeminars',
            },
            {
              name: 'allWordpressWpSeminars',
            },
            {
              name: 'wordpressPage',
            },
            {
              name: 'allWordpressPage',
            },
            {
              name: 'wordpressPost',
            },
            {
              name: 'allWordpressPost',
            },
          ],
        },
      },
    },
  };
});
exports.emptySchemaQueryQraphql = jest.fn().mockImplementation(() => {
  return {
    data: {
      __schema: {
        queryType: {
          fields: [
            //Always an array, because there are other objects like metadata or directories
          ],
        },
      },
    },
  };
});

exports.allWordpressPostQuery = jest.fn().mockImplementation(() => {
  const fields = {
    data: {
      allWordpressPost: {
        edges: [
          {
            node: {
              id: '1262cde2-a078-56ad-a54d-14f50f331880',
              databaseId: 134,
              slug: 'empty-post',
              path: '/empty-post/',
              title: 'Empty post',
            },
          },
          {
            node: {
              id: '6ddb8e23-8bdc-5eb7-aef8-386b0fab59e3',
              databaseId: 88,
              slug: 'lorem-ipsum-i-tak-dalej',
              path: '/lorem-ipsum-i-tak-dalej/',
              title: 'Lorem Ipsum i tak dalej',
            },
          },
          {
            node: {
              id: 'ba8aea61-dd49-5b81-b85e-44d5c39b44db',
              databaseId: 86,
              slug: 'advanced-structure-test',
              path: '/advanced-structure-test/',
              title: 'Advanced structure test',
            },
          },
          {
            node: {
              id: 'c107c87c-5bb4-5e6f-8feb-1c2d11b6573c',
              databaseId: 83,
              slug: 'example-post',
              path: '/example-post/',
              title: 'Example post',
            },
          },
        ],
      },
    },
  };

  return new Promise((resolve, _reject) => {
    setTimeout(() => {
      resolve(fields);
    }, 300);
  });
});
