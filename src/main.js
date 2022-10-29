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
    myDiv.id = 'iframe_container';

    document.body.appendChild(myDiv);

    setTimeout(() => {
        const app = new Vue({
            render: h => h(App),
        }).$mount('#iframe_container');
    }, 1000);

})
