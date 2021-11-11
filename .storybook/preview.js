export const parameters = {
  controls: {
    expanded: true
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
    }
  },
  options: {
    storySort: {
      order: JSON.parse(process.env.STORYBOOK_SORT_ORDER)
    }
  }
};

export const globalTypes = JSON.parse(process.env.STORYBOOK_GLOBAL_TYPES);
