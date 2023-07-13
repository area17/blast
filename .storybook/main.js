const config = {
  stories: ['../stories/**/*.stories.json'],
  addons: [
    '@storybook/addon-links',
    '@storybook/addon-essentials',
    '@storybook/addon-a11y',
    '@storybook/addon-designs',
    'storybook-source-code-addon',
    '@etchteam/storybook-addon-status'
  ],
  docs: {
    autodocs: 'tag',
    // autodocs: true,
    defaultName: 'Docs'
  },
  features: {
    storyStoreV7: false
  },
  framework: {
    name: '@storybook/server-webpack5',
    options: {
      quiet: true,
      port: 6006
    }
  }
};

export default config;
