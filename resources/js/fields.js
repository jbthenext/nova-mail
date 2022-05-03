import FormSendMail from './components/send/FormField';
import IndexField from './components/events/IndexField';
import DetailField from './components/events/DetailField';
import FormField from './components/events/FormField';

Nova.booting((app, store) => {
    app.component('form-send-mail', FormSendMail);
    app.component('index-events', IndexField);
    app.component('detail-events', DetailField);
    app.component('form-events', FormField);
});
