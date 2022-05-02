import FormSendMail from './components/send/FormField';
import IndexField from './components/events/IndexField';
import DetailField from './components/events/DetailField';
import FormField from './components/events/FormField';

Nova.booting((Vue, router, store) => {
    Vue.component('form-send-mail', FormSendMail);
    Vue.component('index-events', IndexField);
    Vue.component('detail-events', DetailField);
    Vue.component('form-events', FormField);
});
