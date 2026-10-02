const fs = require('fs');
const plantumlEncoder = require('plantuml-encoder');
const axios = require('axios');
const path = require('path');

const pumlFiles = fs.readdirSync(path.join(__dirname, 'demo')).filter(f => f.startsWith('sequence_') && f.endsWith('.puml'));
const filesToProcess = pumlFiles.map(f => {
    let name = f.replace('.puml', '.png');
    // Map specific names if needed, but keeping the same is safer
    if (f === 'flowchart_sistem.puml') name = 'flowchart sistem.png';
    if (f === 'usecase_pembimbing.puml') name = 'usecase_pemb lap.png';
    return { puml: f, png: name };
});

async function generate() {
    for (const file of filesToProcess) {
        try {
            const pumlPath = path.join(__dirname, 'demo', file.puml);
            const pumlContent = fs.readFileSync(pumlPath, 'utf8');
            const encoded = plantumlEncoder.encode(pumlContent);
            const url = 'http://www.plantuml.com/plantuml/png/' + encoded;
            
            console.log(`Downloading ${file.png}...`);
            const response = await axios({
                url,
                method: 'GET',
                responseType: 'stream'
            });
            
            const pngPath = path.join(__dirname, 'ara', file.png);
            response.data.pipe(fs.createWriteStream(pngPath));
            console.log(`Saved ${file.png}`);
        } catch (e) {
            console.error(`Failed to process ${file.puml}:`, e.message);
        }
    }
}

generate();
