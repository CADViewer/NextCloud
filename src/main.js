import Vue from 'vue'
import { translate, translatePlural } from '@nextcloud/l10n'
import App from './App.vue'

export const eventBus = new Vue(); // added to trigger inter components call of methods


Vue.config.productionTip = false
Vue.prototype.t = translate
Vue.prototype.n = translatePlural
Vue.prototype.OC = window.OC
Vue.prototype.OCA = window.OCA

// eslint-disable-next-line
__webpack_nonce__ = btoa(OC.requestToken)
// eslint-disable-next-line
__webpack_public_path__ = OC.linkTo('cadviewer', 'js/')



window.addEventListener('DOMContentLoaded', () => {
	
    const myDiv = document.createElement("div");
    myDiv.id = 'cadviewer_app';

    document.body.appendChild(myDiv);

    setTimeout(() => {
        const app = new Vue({
            el: '#cadviewer_app',
            render: h => h(App, {props:{ foo:' source prop'}})
        });
    }, 1000);

})
