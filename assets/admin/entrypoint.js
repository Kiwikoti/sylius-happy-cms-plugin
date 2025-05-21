import {startStimulusApp} from '@symfony/stimulus-bridge';

import PageTreeController from "./controllers/PageTreeController";
import DeletePageController from "./controllers/DeletePageController";

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));

app.register('page-tree', PageTreeController);
app.register('delete-page', DeletePageController);

app.debug = process.env.NODE_ENV !== 'production';
