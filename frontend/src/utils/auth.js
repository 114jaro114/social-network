import decode from 'jwt-decode'
import axios from 'axios'

export function logoutUser() {
  clearAuthToken()
}

export function getAuthToken() {
  return localStorage.getItem('authToken')
}

export function clearAuthToken() {
  axios.defaults.headers.common['Authorization'] = ''
  localStorage.removeItem('authToken')
  // window.location.href = "/login"
}

export function isLoggedIn() {
  let authToken = getAuthToken()
  return !!authToken && !isTokenExpired(authToken)
}

export function getUserInfo() {
  if (isLoggedIn()) {
    return decode(getAuthToken())
  }
}

function getTokenExpirationDate(encodedToken) {
  let token = decode(encodedToken)
  if (!token.exp) {
    localStorage.removeItem('authToken');
    return null
  }

  let date = new Date(0)
  date.setUTCSeconds(token.exp)

  // var date = new Date().getTime() / 1000;
  // if (date > token.exp) {
  //   localStorage.removeItem('authToken');
  //   return null
  // }

  return date
}

function isTokenExpired(token) {
  let expirationDate = getTokenExpirationDate(token)
  return expirationDate < new Date()
}