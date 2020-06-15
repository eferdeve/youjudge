//Client-ID: 6v1t2gynoz847exl4u7l32nft321d2


let clientId = '6v1t2gynoz847exl4u7l32nft321d2';
let LANG = 'fr';

//XMLHttpRequest
function getLoLData(LANG) {
    clearData();
    let apiUrl = `https://api.twitch.tv/kraken/streams/?game=League%20of%20Legends&limit=20&language=${LANG} `;
    let request = new XMLHttpRequest();
    request.open('GET', apiUrl, true);
    request.setRequestHeader('Accept', 'application/vnd.twitchtv.v5+json');
    request.setRequestHeader('Client-ID', clientId);
    request.send();

    request.onload = function load() {

        if (this.status >= 200 && this.status < 400) {
            let data = JSON.parse(this.responseText);
            getData(data, block1);

        } else {
            console.log('Error');
        }
    };
    request.onerror = function error() {
        console.log('Error');
    };
    $('#LolBlock').show();
};

function getValorantData(LANG) {
    clearData();
    let apiUrl = `https://api.twitch.tv/kraken/streams/?game=Valorant&limit=20&language=${LANG} `;
    let request = new XMLHttpRequest();
    request.open('GET', apiUrl, true);
    request.setRequestHeader('Accept', 'application/vnd.twitchtv.v5+json');
    request.setRequestHeader('Client-ID', clientId);
    request.send();

    request.onload = function load() {

        if (this.status >= 200 && this.status < 400) {
            let data = JSON.parse(this.responseText);
            getData(data, block1);

        } else {
            console.log('Error');
        }
    };
    request.onerror = function error() {
        console.log('Error');
    };
    $('#LolBlock').show();
};

function getTftData(LANG) {
    clearData();
    let apiUrl = `https://api.twitch.tv/kraken/streams/?game=Teamfight%20Tactics&limit=20&language=${LANG} `;
    let request = new XMLHttpRequest();
    request.open('GET', apiUrl, true);
    request.setRequestHeader('Accept', 'application/vnd.twitchtv.v5+json');
    request.setRequestHeader('Client-ID', clientId);
    request.send();

    request.onload = function load() {

        if (this.status >= 200 && this.status < 400) {
            let data = JSON.parse(this.responseText);
            getData(data, block1);

        } else {
            console.log('Error');
        }
    };
    request.onerror = function error() {
        console.log('Error');
    };
    $('#LolBlock').show();
};

function getDestiny2Data(LANG) {
    clearData();
    let apiUrl = `https://api.twitch.tv/kraken/streams/?game=Destiny%202&limit=10&language=${LANG} `;
    let request = new XMLHttpRequest();
    request.open('GET', apiUrl, true);
    request.setRequestHeader('Accept', 'application/vnd.twitchtv.v5+json');
    request.setRequestHeader('Client-ID', clientId);
    request.send();

    request.onload = function load() {

        if (this.status >= 200 && this.status < 400) {
            let data = JSON.parse(this.responseText);
            getData(data, block1);

        } else {
            console.log('Error');
        }
    };
    request.onerror = function error() {
        console.log('Error');
    };
    $('#LolBlock').show();
};

//JQuery AJAX
function getMSData(LANG) {
    clearData();
    let apiUrl2 = `https://api.twitch.tv/kraken/streams/?game=MapleStory&limit=6&language=${LANG} `;

    // AJAX寫法1
    // $.ajax({
    //     url: apiUrl2,
    //     headers: {
    //         'Accept': 'application/vnd.twitchtv.v5+json',
    //         'client-ID': clientId,
    //     },
    //     success: (data2) => {
    //         getData(data2, block2);
    //     },
    //     error: function () {
    //         console.log('JQuery Ajax Error');
    //     }
    // })

    // AJAX寫法2
    $.ajax({
        url: apiUrl2,
        headers: {
            'Accept': 'application/vnd.twitchtv.v5+json',
            'client-ID': clientId,
        }
    })
    .done(data2 => getData(data2, block2))
    .fail(() => console.log('JQuery Ajax Error'))
    .always(()=>console.log('complete'));



    $('#LolBlock').hide();
    $('#MSBlock').show();
};

function getData(data, block) {
    const streams = data.streams;
    const $row = $(block);
    for (var i = 0; i < streams.length; i++) {
        $row.append(getColumn(streams[i]))
    }
};

function getColumn(data) {
    return `
    <table class="table table" id="myTable">
    <thead class="thead-dark">
      <tr>
        <th class="text-center" scope="col">Nom</th>
        <th class="text-center" scope="col">Chaîne</th>
        <th class="text-center" scope="col">Intitulé du stream</th>
        <th class="text-center" scope="col">Viewers</th>
      </tr>
    </thead>
    <tbody>
      <tr>
          <td class="text-center"><span id="nom">${data.channel.display_name}</span></td>
          <td class="text-center"><span id="chaine"><a href="https://www.twitch.tv/${data.channel.name}" target="blank"> ${data.channel.name}</a></span></td>
          <td class="text-center"><span id="status">${data.channel.status}</span></td>
          <td class="text-center text-danger"><span id="jeu"><i class="fa fa-user"></i> ${data.viewers}</span></td>
      </tr>
    </tbody>
    </table>
    `
    ;
};

function clearData() {
    $('.row').empty();
}

