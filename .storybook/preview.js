import '../public/main.css';
import { themes } from '@storybook/theming';
import theme from './theme';

let setDocsTheme = (configDocsTheme) => {
  if (configDocsTheme === 'dark') {
    return themes.dark;
  } else if (configDocsTheme === 'custom') {
    return theme;
  } else {
    return themes.normal;
  }
};

const customViewports = JSON.parse(process.env.STORYBOOK_VIEWPORTS);

const fetchStoryHtml = async (url, path, params, context) => {
  const fetchUrl = new URL(`${url}/${path}`);
  fetchUrl.search = new URLSearchParams({
    ...context.globals,
    ...params
  }).toString();

  const headers = new Headers();

  if (process.env.STORYBOOK_SERVER_AUTH) {
    headers.append(
      'Authorization',
      `Basic ${btoa(process.env.STORYBOOK_SERVER_AUTH)}`
    );
  }

  const response = await fetch(fetchUrl, {
    method: 'GET',
    headers
  });

  const html = await response.text();

  return html;
};

const preview = {
  parameters: {
    viewport: {
      viewports: customViewports
    },
    controls: {
      expanded: JSON.parse(process.env.STORYBOOK_EXPANDED_CONTROLS)
    },
    server: {
      url: process.env.STORYBOOK_SERVER_URL,
      fetchStoryHtml
    },
    layout: 'centered',
    status: {
      statuses: JSON.parse(process.env.STORYBOOK_STATUSES)
    },
    docs: {
      extractComponentDescription: (component, { notes }) => {
        if (notes) {
          return typeof notes === 'string'
            ? notes
            : notes.markdown || notes.text;
        }
        return null;
      },
      theme: setDocsTheme(JSON.parse(process.env.STORYBOOK_DOCS_THEME))
    },
    options: {
      storySort: {
        order: JSON.parse(process.env.STORYBOOK_SORT_ORDER)
      }
    }
  },
  globalTypes: JSON.parse(process.env.STORYBOOK_GLOBAL_TYPES)
};

export default preview;
