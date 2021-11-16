import { addons } from '@storybook/addons';
import { themes } from '@storybook/theming';
import theme from './theme';

const configTheme = JSON.parse(process.env.STORYBOOK_THEME);

let storybookTheme = () => {
  if (configTheme === "dark") {
    addons.setConfig({ theme: themes.dark });
  } else {
    addons.setConfig({ theme: themes.normal });
  }
}

let customTheme = () => {
  addons.setConfig({ theme });
}

if (typeof configTheme !== 'string') {
  customTheme();
} else {
  if (configTheme === "custom") {
    customTheme();
  } else {
    storybookTheme();
  }
}