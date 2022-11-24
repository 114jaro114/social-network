//imports the Web3 API
import Web3 from 'web3';
/**
 * creates & exports new instance for
 * Web3 using provided service by Metamask.
 */
let currentWeb3;

if (window.ethereum) {
  let instance = new Web3(window.ethereum);
  try {
    // Request account access if needed
    if (window.ethereum.isConnected()) {
      window.ethereum.enable();
      // window.ethereum.sendAsync('eth_requestAccounts');
    }
    // Acccounts now exposed
    currentWeb3 = instance;

  } catch (error) {
    // User denied account access...
    alert('Please allow access for the app to work');
  }
} else if (window.web3) {
  window.ethereum.enable();
  // window.ethereum.sendAsync('eth_requestAccounts');
  window.web3 = new Web3(new Web3.providers.HttpProvider('https://ropsten.infura.io/v3/9ef3562b49cb43458d202d8e3e4c258f'))
  // currentWeb3 = new Web3(new Web3.currentProvider);
  // Acccounts always exposed
} else {
  console.log('Non-Ethereum browser detected. You should consider trying MetaMask!');
}

// import detectEthereumProvider from '@metamask/detect-provider';
//
// const provider = detectEthereumProvider();
//
// provider.then(function(result) {
//   if (result != null) {
//     if (window.ethereum != undefined) {
//       let instance = new Web3(window.ethereum);
//       try {
//         // Request account access if needed
//         if (window.ethereum.isConnected()) {
//           window.ethereum.enable();
//         }
//         // Acccounts now exposed
//         currentWeb3 = instance;
//
//       } catch (error) {
//         // User denied account access...
//         currentWeb3 = "Please allow access for the app to work";
//         console.log('Please allow access for the app to work');
//       }
//     }
//   } else {
//     let test = "Non-Ethereum browser detected";
//     currentWeb3 = test;
//     console.log('Non-Ethereum browser detected. You should consider trying MetaMask!');
//     return 'hhh';
//   }
// });

export default currentWeb3;