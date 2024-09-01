Sviluppa una Web per la pianificazione e gestione del tuo viaggio. Suddividi il viaggio in giornate in cui ogni giornata riporta le tappe da visitare. Inserisci dettagli (titolo, descrizione, data etc), immagini, cibo, curiosità o qualsiasi altra info. 

Le tappe del viaggio saranno anche visualizzate su mappa tramite un servizio a tua scelta. 

Implementa una funzionalità per tenere traccia della progressione delle tappe del tuo viaggio (anche quando chiudi la pagina). 

Aggiungi personalizzazioni e funzionalità a tuo piacimento. Alcuni esempi:

- aprire dettaglio viaggio o immagini in una modale
- possibilità di aggiungere note a una tappa durante il viaggio
- possibilità di inserire una valutazione (ad es. da 1 a 5 stelle) su una tappa

Carica il codice su un servizio di hosting a tua scelta per condividere l’app.


## Appunti
1. Scrivere un README Dettagliato
2. Individuare possibili funzionalità ed eventuali tecnologie.
3. Sviluppo di un database creando le tabelle utilizzando Laravel.Tra cui trips, stages, stage_trip, notes.
Crud sullle giornate e sulle tappe.(opzione più semplice fare il backend con php plain e delle api il backoffice, lo gestirei con il fronted(Vue) e sempre con utilizzo delle api).
4. Visualizzare le tappe usando(TomTom).

## Milestone:
1. Progettazione e pianificazione
Definire i requisiti funzionali e non funzionali dell'app e creare un wireframe dell'interfaccia utente. Progettare una soluzione tecnologica adeguata. Pianificare le attività da svolgere per arrivare alla deadline.
2. Setup ambiente e progetto
Configurare l’ambiente di sviluppo se necessario e definire un setup di progetto (strumenti, build tools, git etc..). Inizializzare il progetto.
3. Definizione di uno stato (struttura dei dati)
Definire la struttura dati idonea a rappresentare un viaggio e tutti i suoi dettagli, nonché le scelte e i dati inseriti dall’utente.
3. Sviluppo layout e componenti UI
Sviluppo della struttura di layout e dei componenti della pagina.
4. Logica applicativa
Logica di render dell’interfaccia a partire dal dato strutturato. Aggiungi le varie modalità di visualizzazione e interazioni.
5. Persistenza
Implementare la funzionalità per salvare la progressione delle tappe (es. LocalStorage o backend) e garantire che i dati siano mantenuti anche dopo la chiusura della pagina.
6. Deployment
Caricare il codice su un servizio di hosting a tua scelta (Netlify, Vercel, Heroku, ecc.).
7. Documentazione
Scrivere un README da aggiungere alla repository per documentare requisiti, installazione e funzionalità.
## Bonus 
1. Più viaggi! (BONUS)
Visualizzare più di un viaggio selezionandoli da un menu.
2. PWA (BONUS 2)
Integra alcune funzionalità che caratterizzano una Progressive Web Application e che rendono la tua app simile a un’app nativa! Abbiamo parlato di questo durante una masterclass sulle PWA (Progressive Web Apps).



## Deploy
1.Before deployment, ensure your Vue.js application is ready for production. Run the build command to generate the optimized and minified files needed for deployment:
- npm run build

This command creates a “dist” folder containing the production-ready files.