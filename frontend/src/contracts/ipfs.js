//imports the IPFS API
import {
  create
} from 'ipfs-http-client'

const ipfs = create({
  host: 'ipfs.infura.io',
  port: '5001',
  protocol: 'https',
  // apiPath: '/api/v0'
})

export default ipfs;