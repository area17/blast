module.exports = {
  stories: ['../stories/**/*.stories.mdx', '../stories/**/*.stories.@(json)'],
  addons: [
    '@storybook/addon-postcss',
    '@storybook/addon-links',
    '@storybook/addon-essentials',
    '@storybook/addon-a11y',
    'storybook-addon-designs',
    'storybook-source-code-addon',
    '@etchteam/storybook-addon-status'
  ],
  core: {
    builder: 'webpack5'
  }
};
