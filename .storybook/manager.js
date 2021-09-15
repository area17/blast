
import { addons } from '@storybook/addons';
import theme from './theme';

if(process.env.STORYBOOK_THEME){
  addons.setConfig({
    theme,
  });
}
