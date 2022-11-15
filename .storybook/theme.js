import { create } from '@storybook/theming';

const configTheme = JSON.parse(process.env.STORYBOOK_THEME);

let theme;

if (typeof configTheme !== 'string') {
  theme = create(JSON.parse(process.env.STORYBOOK_THEME));
} else {
  if (configTheme === 'custom') {
    theme = create(JSON.parse(process.env.STORYBOOK_CUSTOM_THEME));
  }
}

export default theme;
