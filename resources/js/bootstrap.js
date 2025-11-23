import axios from 'axios';
import Chart from 'chart.js/auto';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Chart = Chart;
