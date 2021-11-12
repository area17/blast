import { themes } from '@storybook/theming';

let setDocsTheme = (configDocsTheme) => {
  if (configDocsTheme === "dark") {
    return themes.dark;
  } else {
    return themes.normal;
  }
}

export const parameters = {
  controls: {
    expanded: JSON.parse(process.env.STORYBOOK_EXPANDED_CONTROLS),
  },
  server: {
    url: process.env.STORYBOOK_SERVER_URL
  },
  layout: 'centered',
  status: {
    statuses: JSON.parse(process.env.STORYBOOK_STATUSES)
  },
  docs: {
    extractComponentDescription: (component, { notes }) => {
      if (notes) {
        return typeof notes === 'string' ? notes : notes.markdown || notes.text;
      }
      return null;
    },
    theme: setDocsTheme(JSON.parse(process.env.STORYBOOK_DOCS_THEME)),
  },
  options: {
    storySort: {
      order: JSON.parse(process.env.STORYBOOK_SORT_ORDER)
    }
  }
};

export const globalTypes = JSON.parse(process.env.STORYBOOK_GLOBAL_TYPES);