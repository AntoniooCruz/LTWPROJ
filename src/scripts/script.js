'use strict'

let inputs = document.querySelectorAll("#sorting input")

var session

let request = new XMLHttpRequest()
request.addEventListener('load', getSession)
request.open('get', '../api/api_get_session.php', true)
request.send()
    
if(inputs.length != 0){
    inputs[0].addEventListener("click", getTopStories)
    
    inputs[1].addEventListener("click", function(){
        console.log("new")
    })
}

function getSession(event){
    event.preventDefault()

    session = JSON.parse(this.responseText)
}

function getTopStories(event){
    event.preventDefault()

    let request = new XMLHttpRequest()
    request.addEventListener('load', handler)
    request.open('get', '../api/api_get_stories.php', true)
    request.send()
}

function handler(event){
    event.preventDefault()

    let newStories = JSON.parse(this.responseText)

    console.log(newStories)

    let stories = document.querySelectorAll("#list div")

    stories.forEach(function(data){
        data.remove()
    })

    let list = document.querySelector("#list")

    newStories.forEach(function(data){
        let story = document.createElement("article")
        story.classList.add("story")

        story.innerHTML = `
        <div class="titles">
        <header><a href="../pages/story.php?id=`+data.post_id+`">`+data.post_title+`</a></header>
        <ul>
        `+getTags(data.tags)+
        `
        </ul>
        <footer>Submitted by: `+data.user_name+` on `+data.post_date+` to <a href="../pages/channel.php?id=`+data.channel_id+`">`+data.channel+`</a></footer>
        <div class="voteup">
        <form method="post" action="../actions/action_vote.php">
        <button name="upvote" type="submit"> <i class="fas fa-chevron-up"></i> </button>
        <input type="hidden" name="post_op" value="`+data.post_op+`>">
        <input type="hidden" name="post_id" value="`+data.post_id+`">
        <input type="hidden" name="type" value="upvote">
        <input type="hidden" name="csrf" value="`+session.csrf+`">
        </form>
        </div>
        <div class = "votedown">
        <form method="post" action="../actions/action_vote.php">
        <button name="downvote" type="submit">  <i class="fas fa-chevron-down"></i> </button>
        <input type="hidden" name="post_op" value="`+data.post_op+`">
        <input type="hidden" name="post_id" value="`+data.post_id+`">
        <input type="hidden" name="type" value="downvote">
        <input type="hidden" name="csrf" value="`+session.csrf+`">
        </form>
        </div>

        <p>`+data.num_votes+`</p>
        </div>`

        list.append(story)
    })
}

function getTags(tags){
    let string = ""

    tags.forEach(function(tag){
        string += "<li>"+tag.tag_text+"</li>\n"
    })

    return string
}


function encodeForAjax(data) {
  return Object.keys(data).map(function(k){
    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&')
}