let updateBtn = document.querySelectorAll(".updateCommentBtn");

console.log(updateBtn);
updateBtn.forEach(element => {
    element.addEventListener('click', (e) => {

        e.preventDefault();
        e.stopPropagation();

        let commentContainer = e.target.parentNode;
        let commentContent = commentContainer.querySelector('.commentContent').innerText;
        let commentId = commentContainer.querySelector('.commentId').value;

        let form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", "/post/comment/update");

        let separateLine = document.createElement('p')
        separateLine.innerText = "-----------------";

        let inputContent = document.createElement("textarea");
        inputContent.setAttribute("name", "content");
        inputContent.innerText = commentContent;

        let inputCommentId = document.createElement('input');
        inputCommentId.setAttribute("type", "hidden");
        inputCommentId.setAttribute("name", "commentId");
        inputCommentId.setAttribute("value", commentId);

        let submitBtn = document.createElement('button');
        submitBtn.setAttribute("type", "submit");
        submitBtn.innerText = 'Modifier';

        form.appendChild(separateLine);
        form.appendChild(inputContent);
        form.appendChild(inputCommentId);
        form.appendChild(submitBtn);

        commentContainer.replaceWith(form);

    })
});
