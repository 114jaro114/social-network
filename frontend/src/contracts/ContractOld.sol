pragma solidity 0.6.0;
pragma experimental ABIEncoderV2;
//import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/v3.4.0/contracts/math/SafeMath.sol";
import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/v3.4.0/contracts/math/SafeMath.sol";


contract InstagramPosting{
    using SafeMath for uint256;

    // User profile
    struct User {
        bytes32 id;
        bytes32 name;
        bytes32 email;
        bytes32 avatar;
    }

    // This struct is for the properties of a post.
    struct Post{
        address owner;
        string imgHash;
        string videoHash;
        string textHash;
        uint256 timestamp;
        bytes32 ownerId;
        bytes32 toUserId;
    }

    // All the users ever added
    User[] public users;

    // Each address is linked to a user with name, occupation and bio
    mapping(address => User) public userInfo;

    // Each address is linked to several follower addresses
    mapping(address => address[]) public userFollowers;

    // A mapping list for posts from Post struct.
    mapping(uint256 => Post) posts;

    // A counter for the posts mapping list.
    uint256 postCtr;

    // Event which will notify new posts.
    event NewPost();

    // Event which will notify new posts.
    event UpdateUserInfo();

    // @dev Function to store image & text hashes.
    // @param _img hash from IPFS.
    // @param _text hash from IPFS.
    function sendHash(
        string memory _img,
        string memory _video,
        string memory _text,
        // bytes32 _ownerid,
        bytes32 _touserid
    )
        public
    {
        postCtr = postCtr.add(1);
        Post storage posting = posts[postCtr];
        posting.owner = msg.sender;
        posting.imgHash = _img;
        posting.videoHash = _video;
        posting.textHash = _text;
        posting.timestamp = now;
        // posting.ownerId = _ownerid;
        posting.ownerId = userInfo[msg.sender].id;
        posting.toUserId = _touserid;

        emit NewPost();
    }


    // @dev Function to get image & text hashes.
    // @param _index number from total posts iteration.
    // @return Stored image & text hashes.

    function getHash(uint256 _index)
        public
        view
        returns (
            string memory img,
            string memory video,
            string memory text,
            address owner,
            uint256 date,
            bytes32 ownerid,
            bytes32 touserid
        )
    {
        owner = posts[_index].owner;
        img = posts[_index].imgHash;
        video = posts[_index].videoHash;
        text = posts[_index].textHash;
        date = posts[_index].timestamp;
        ownerid = posts[_index].ownerId;
        touserid = posts[_index].toUserId;
    }

    // @dev Function to get length of total posts.
    // @return The total count of posts.
    function getCounter() public view returns(uint256) { return postCtr; }

    // Sets the profile of a user
    function setProfile(bytes32 _id, bytes32 _name, bytes32 _email, bytes32 _avatar) public {
        User memory user = User( _id, _name, _email, _avatar);
        userInfo[msg.sender] = user;
        users.push(user);
    }

    function getUsers()public view returns(User[] memory){
        return users;
    }

     function updateUserInfo(address _user, bytes32 _avatar) public {
        userInfo[_user].avatar = _avatar;
        for(uint i = 0; i < users.length; i++) {
            if(users[i].id == userInfo[_user].id) {
                users[i].avatar = _avatar;
            }
        }

        emit UpdateUserInfo();
    }

    // Follows a new user
    function followUser(address _user) public {
        userFollowers[msg.sender].push(_user);
    }

    // Unfollows a user
    function unfollowUser(address _user) public {
        for(uint i = 0; i < userFollowers[msg.sender].length; i++) {
            if(userFollowers[msg.sender][i] == _user) {
                delete userFollowers[msg.sender][i];
            }
        }
    }
}
