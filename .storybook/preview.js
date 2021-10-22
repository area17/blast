import '../public/main.css';

export const parameters = {
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
  }
};

export const globalTypes = JSON.parse(process.env.STORYBOOK_GLOBAL_TYPES);
