import {startStimulusApp} from '@symfony/stimulus-bridge';

import LiveController from '@symfony/ux-live-component';
import PageTreeController from "./controllers/PageTreeController";
import DeletePageController from "./controllers/DeletePageController";

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));

app.register('live', LiveController);
app.register('happycms-page-tree-tree', PageTreeController);
app.register('happycms-page-tree-delete', DeletePageController);

app.debug = process.env.NODE_ENV !== 'production';
