import web3 from './web3';

// const address = "0xFFc6e0f5e0a97662B01d05E3B4429874B45D42C3";

const address = "0x5a82F64fE8C07A79f6dF6FCdbfFb7714A68b7E6C";

const abi = [{
    "inputs": [{
      "internalType": "address",
      "name": "_user",
      "type": "address"
    }],
    "name": "followUser",
    "outputs": [],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "anonymous": false,
    "inputs": [],
    "name": "NewPost",
    "type": "event"
  },
  {
    "inputs": [{
        "internalType": "bytes32",
        "name": "_ownName",
        "type": "bytes32"
      },
      {
        "internalType": "string",
        "name": "_img",
        "type": "string"
      },
      {
        "internalType": "string",
        "name": "_video",
        "type": "string"
      },
      {
        "internalType": "string",
        "name": "_text",
        "type": "string"
      },
      {
        "internalType": "bytes32",
        "name": "_ownID",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "_touserid",
        "type": "bytes32"
      }
    ],
    "name": "sendHash",
    "outputs": [],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "inputs": [{
        "internalType": "bytes32",
        "name": "_id",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "_name",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "_email",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "_avatar",
        "type": "bytes32"
      }
    ],
    "name": "setProfile",
    "outputs": [],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "anonymous": false,
    "inputs": [],
    "name": "UpdateUserInfo",
    "type": "event"
  },
  {
    "anonymous": false,
    "inputs": [],
    "name": "setUserInfo",
    "type": "event"
  },
  {
    "inputs": [{
      "internalType": "address",
      "name": "_user",
      "type": "address"
    }],
    "name": "unfollowUser",
    "outputs": [],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "inputs": [{
        "internalType": "address",
        "name": "_user",
        "type": "address"
      },
      {
        "internalType": "bytes32",
        "name": "_avatar",
        "type": "bytes32"
      }
    ],
    "name": "updateUserInfo",
    "outputs": [],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "inputs": [],
    "name": "getCounter",
    "outputs": [{
      "internalType": "uint256",
      "name": "",
      "type": "uint256"
    }],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [{
      "internalType": "uint256",
      "name": "_index",
      "type": "uint256"
    }],
    "name": "getHash",
    "outputs": [{
        "internalType": "bytes32",
        "name": "owner",
        "type": "bytes32"
      },
      {
        "internalType": "string",
        "name": "img",
        "type": "string"
      },
      {
        "internalType": "string",
        "name": "video",
        "type": "string"
      },
      {
        "internalType": "string",
        "name": "text",
        "type": "string"
      },
      {
        "internalType": "uint256",
        "name": "date",
        "type": "uint256"
      },
      {
        "internalType": "bytes32",
        "name": "ownerid",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "touserid",
        "type": "bytes32"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [{
      "internalType": "bytes32",
      "name": "_id",
      "type": "bytes32"
    }],
    "name": "getMe",
    "outputs": [{
        "internalType": "bytes32",
        "name": "",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "",
        "type": "bytes32"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [],
    "name": "getUsers",
    "outputs": [{
      "components": [{
          "internalType": "bytes32",
          "name": "id",
          "type": "bytes32"
        },
        {
          "internalType": "bytes32",
          "name": "name",
          "type": "bytes32"
        },
        {
          "internalType": "bytes32",
          "name": "email",
          "type": "bytes32"
        },
        {
          "internalType": "bytes32",
          "name": "avatar",
          "type": "bytes32"
        }
      ],
      "internalType": "struct Contract.User[]",
      "name": "",
      "type": "tuple[]"
    }],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [{
      "internalType": "bytes32",
      "name": "",
      "type": "bytes32"
    }],
    "name": "me",
    "outputs": [{
        "internalType": "bytes32",
        "name": "id",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "name",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "email",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "avatar",
        "type": "bytes32"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [{
        "internalType": "address",
        "name": "",
        "type": "address"
      },
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "name": "userFollowers",
    "outputs": [{
      "internalType": "address",
      "name": "",
      "type": "address"
    }],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [{
      "internalType": "address",
      "name": "",
      "type": "address"
    }],
    "name": "userInfo",
    "outputs": [{
        "internalType": "bytes32",
        "name": "id",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "name",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "email",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "avatar",
        "type": "bytes32"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [{
      "internalType": "uint256",
      "name": "",
      "type": "uint256"
    }],
    "name": "users",
    "outputs": [{
        "internalType": "bytes32",
        "name": "id",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "name",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "email",
        "type": "bytes32"
      },
      {
        "internalType": "bytes32",
        "name": "avatar",
        "type": "bytes32"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  }
]



let contract;

if (web3 != undefined) {
  contract = new web3.eth.Contract(abi, address);
} else {
  if (window.ethereum) {
    contract = "Please login to MetaMask";
  } else {
    contract = "Non-Ethereum browser detected";
  }
}

export default contract