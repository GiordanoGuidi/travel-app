import { createApp } from 'vue'
import 'bootstrap/dist/css/bootstrap.min.css';
import App from './App.vue'
// Importiamo la libreria generica di FontAwesome
import { library } from '@fortawesome/fontawesome-svg-core'
// Importiamo il componente di FontAwesome
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
// Importiamo le icone da utilizzare
import {
    faArrowLeft, faMagnifyingGlass,
    faArrowDown, faChevronRight, faPlus,
    faMinus, faTrashCan
} from '@fortawesome/free-solid-svg-icons'
// Icone da caricare
library.add(faArrowLeft, faMagnifyingGlass, faArrowDown,
    faChevronRight, faPlus, faMinus, faTrashCan);
import { router } from './router'

const app = createApp(App);
// Rendiamo le icone disponibili globalmente
app.component('FontAwesomeIcon', FontAwesomeIcon);
app.use(router);
app.mount('#app');
