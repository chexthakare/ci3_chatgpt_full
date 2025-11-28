<!DOCTYPE html>
<html>
<head>
    <title>Chat (OpenAI / Gemini)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        #chatbox {
            border:1px solid #ddd;
            padding:10px;
            width:80%;
            height:400px;
            overflow-y:auto;
            background:#f9f9f9;
            border-radius:5px;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }
        .message {
            margin-bottom:12px;
            padding:12px 16px;
            border-radius:12px;
            max-width:70%;
            word-wrap:break-word;
            font-size: 15px;
            line-height: 1.4;
            box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
            position: relative;
        }
        .user-msg {
            background:#0d6efd;
            color:white;
            margin-left:auto;
            text-align:right;
            border-bottom-right-radius: 0;
        }
        .ai-msg {
            background:#e9ecef;
            color:#000;
            margin-right:auto;
            text-align:left;
            border-bottom-left-radius: 0;
            white-space: pre-wrap;
        }
        .copy-btn {
            position:absolute;
            top:8px;
            right:40px;
            background:transparent;
            border:none;
            cursor:pointer;
            font-size:16px;
            opacity: 0.5;
            transition: opacity 0.2s ease-in-out;
        }
        .copy-btn:hover { opacity:1; }
        .summarize-btn {
            position:absolute;
            top:8px;
            right:8px;
            font-size: 12px;
            padding: 2px 6px;
            cursor:pointer;
            border:none;
            background-color: #0d6efd;
            color: white;
            border-radius: 4px;
            opacity: 0.8;
            transition: opacity 0.2s ease-in-out;
        }
        .summarize-btn:hover { opacity:1; }
        #thinking {
            font-style: italic;
            color: gray;
            margin-left: auto;
            margin-bottom: 12px;
            max-width: 70%;
        }

        .copy-btn, .summarize-btn {
            position: relative;
            margin-right: 10px;
            font-size: 13px;
            padding: 4px 10px;
            border: none;
            cursor: pointer;
            border-radius: 6px;
        }

        .copy-btn {
            background-color: #6c757d;
            color: white;
        }

        .summarize-btn {
            background-color: #0d6efd;
            color: white;
        }

        .ai-actions {
            margin-top: 10px;
            display: flex;
            gap: 10px;
            margin-left: 5%;
            margin-bottom: 2%;
        }

    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Chat with AI</h2>

    <div class="mb-3">
        <label for="provider" class="form-label">Choose Provider:</label>
        <select id="provider" class="form-select" style="width:200px;">
            <!-- <option value="openai">OpenAI</option> -->
            <option value="gemini">Google Gemini</option>
        </select>
    </div>

    <div class="input-group mb-3" style="width:80%;">
        <input type="text" id="msg" class="form-control" placeholder="Type your message...">
        <button class="btn btn-primary" onclick="send()">Send</button>
    </div>

    <div id="chatbox"></div>
</div>

<div class="container mt-5">
    <h4>Text Summarization</h4>
    <div class="mb-3" style="width:80%;">
        <textarea id="summary_text" class="form-control" rows="6" placeholder="Paste text here to summarize..."></textarea>
    </div>
    <button id="summarize_text_btn" class="btn btn-success">Summarize</button>

    <div id="summary_result" style="margin-top:15px; max-width:80%;"></div>
</div>


<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
function send() {
    const msgInput = document.getElementById('msg');
    const msg = msgInput.value.trim();
    const provider = document.getElementById('provider').value;
    if(msg === "") return;

    const chatbox = document.getElementById('chatbox');

    const userDiv = document.createElement('div');
    userDiv.classList.add('message', 'user-msg');
    userDiv.textContent = msg;
    chatbox.appendChild(userDiv);

    const thinkingDiv = document.createElement('div');
    thinkingDiv.classList.add('message', 'ai-msg', 'd-flex', 'align-items-center');
    thinkingDiv.id = 'thinking';
    thinkingDiv.innerHTML = `
        <div class="spinner-border spinner-border-sm text-secondary me-2" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span>AI is thinking...</span>
    `;
    chatbox.appendChild(thinkingDiv);

    chatbox.scrollTop = chatbox.scrollHeight;
    msgInput.value = '';
    msgInput.focus();

    fetch("<?= base_url('chat/ask') ?>?provider=" + provider, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "message=" + encodeURIComponent(msg)
    })
    .then(r => r.json())
    .then(data => {
        thinkingDiv.remove();

        const aiDiv = document.createElement('div');
        aiDiv.classList.add('message', 'ai-msg');
        aiDiv.innerHTML = marked.parse(data.reply);

        /* ---------------------------
           BUTTONS BELOW TEXT
        --------------------------- */
        const actions = document.createElement('div');
        actions.className = 'ai-actions';
        actions.style.marginTop = "10px";
        actions.style.display = "flex";
        actions.style.gap = "10px";

        const copyBtn = document.createElement('button');
        copyBtn.className = 'copy-btn';
        copyBtn.innerHTML = '📋 Copy';
        copyBtn.onclick = () => copyText(copyBtn);
        actions.appendChild(copyBtn);

        const summarizeBtn = document.createElement('button');
        summarizeBtn.className = 'summarize-btn';
        summarizeBtn.textContent = 'Summarize';
        summarizeBtn.onclick = () => summarizeText(summarizeBtn);
        actions.appendChild(summarizeBtn);

        aiDiv.appendChild(actions);

        chatbox.appendChild(aiDiv);
        chatbox.scrollTop = chatbox.scrollHeight;
    })
    .catch(err => {
        thinkingDiv.textContent = '⚠️ Error: ' + err;
    });
}

function copyText(btn) {
    const aiBubble = btn.closest(".ai-msg");
    let text = aiBubble.innerText.replace("📋 Copy","").replace("Summarize","").trim();

    navigator.clipboard.writeText(text).then(() => {
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = '📋 Copy', 1500);
    });
}

function summarizeText(btn) {
    const aiBubble = btn.closest(".ai-msg");
    let text = aiBubble.innerText.replace("📋 Copy","").replace("Summarize","").trim();
    const provider = document.getElementById('provider').value;

    btn.disabled = true;
    btn.textContent = 'Summarizing...';

    fetch("<?= base_url('chat/summarize') ?>?provider=" + provider, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "text=" + encodeURIComponent(text)
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.textContent = 'Summarize';

        if(data.summary) {
            const summaryDiv = document.createElement('div');
            summaryDiv.classList.add('message', 'ai-msg');
            summaryDiv.style.backgroundColor = '#d1e7dd';
            summaryDiv.style.fontStyle = 'italic';
            summaryDiv.innerHTML = `<strong>Summary:</strong><br>` + marked.parse(data.summary);

            aiBubble.insertAdjacentElement('afterend', summaryDiv);
            summaryDiv.scrollIntoView({behavior: 'smooth'});
        } else {
            alert("Summary failed.");
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.textContent = 'Summarize';
        alert("Error summarizing: " + err);
    });
}


/* Dedicated Summarize Textarea */
document.getElementById('summarize_text_btn').addEventListener('click', function() {
    const btn = this;
    const text = document.getElementById('summary_text').value.trim();
    const provider = document.getElementById('provider').value;
    const resultDiv = document.getElementById('summary_result');

    if (!text) {
        alert("Please enter text to summarize.");
        return;
    }

    btn.disabled = true;
    btn.textContent = "Summarizing...";

    fetch("<?= base_url('chat/summarize') ?>?provider=" + provider, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "text=" + encodeURIComponent(text)
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.textContent = "Summarize";

        if(data.summary) {
            resultDiv.innerHTML = `<div class="alert alert-info"><strong>Summary:</strong><br>` + marked.parse(data.summary) + `</div>`;
            resultDiv.scrollIntoView({behavior: "smooth"});
        } else {
            resultDiv.innerHTML = `<div class="alert alert-warning">Summary failed.</div>`;
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.textContent = "Summarize";
        resultDiv.innerHTML = `<div class="alert alert-danger">Error summarizing: ${err}</div>`;
    });
});
</script>



</body>
</html>
