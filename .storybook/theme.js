import { create } from '@storybook/theming';

export default create(JSON.parse(process.env.STORYBOOK_CUSTOM_THEME));
