import { create } from '@storybook/theming';

let theme;

if (process.env.STORYBOOK_THEME) {
  const configTheme = JSON.parse(process.env.STORYBOOK_THEME);

  if (typeof configTheme !== 'string') {
    theme = create(JSON.parse(process.env.STORYBOOK_THEME));
  } else {
    if (configTheme === 'custom') {
      theme = create(JSON.parse(process.env.STORYBOOK_CUSTOM_THEME));
    }
  }
}

export default theme;
