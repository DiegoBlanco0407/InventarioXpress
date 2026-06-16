import axios from 'axios'
window.axios = axios

// Identify AJAX requests and prefer JSON responses from Laravel
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
window.axios.defaults.headers.common['Accept'] = 'application/json'

export default axios
