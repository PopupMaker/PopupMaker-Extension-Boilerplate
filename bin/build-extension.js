const fs = require('fs');
const path = require('path');
const { promisify } = require('util');
const readdir = promisify(fs.readdir);
const readFile = promisify(fs.readFile);
const writeFile = promisify(fs.writeFile);
const stat = promisify(fs.stat);
const mkdir = promisify(fs.mkdir);
const rmdir = promisify(fs.rmdir);
const unlink = promisify(fs.unlink);

const variablesPath = './variables.json';
const sourceDir = './plugin-name'; // Adjust this path as necessary
let variables = {};

async function readVariables() {
    try {
        const data = await readFile(variablesPath, 'utf8');
        variables = JSON.parse(data);
    } catch (err) {
        console.error('Error reading variables file:', err);
        process.exit(1);
    }
}

async function deleteFolderRecursive(directoryPath) {
    if (fs.existsSync(directoryPath)) {
        for (let entry of await readdir(directoryPath)) {
            const currentPath = path.join(directoryPath, entry);
            if ((await stat(currentPath)).isDirectory()) {
                // Recurse
                await deleteFolderRecursive(currentPath);
            } else {
                // Delete file
                await unlink(currentPath);
            }
        }
        await rmdir(directoryPath);
    }
}

async function cloneAndReplace(directory, newDirectory) {
    try {
        await mkdir(newDirectory, { recursive: true });
        const items = await readdir(directory);
        for (let item of items) {
            const oldPath = path.join(directory, item);
            const newPath = path.join(newDirectory, item.replace('plugin-name', variables.PLUGIN_SLUG));
            const itemStat = await stat(oldPath);
            if (itemStat.isDirectory()) {
                await cloneAndReplace(oldPath, newPath);
            } else {
                console.log(`Processing file: ${oldPath}`); // Log the file being processed
                let content = await readFile(oldPath, 'utf8');
                Object.keys(variables).forEach((key) => {
                    // Adjusted regex to match single-brace placeholders
                    const regex = new RegExp(`{${key}}`, 'g');
                    content = content.replace(regex, variables[key]);
                });
                await writeFile(newPath, content, 'utf8');
                console.log(`Replaced content in: ${newPath}`); // Log after processing
            }
        }
    } catch (err) {
        console.error('Error cloning and replacing:', err);
    }
}

async function main() {
    await readVariables();
    const newDirectory = `./${variables.PLUGIN_SLUG}`;

    // Delete the old directory if it exists
    console.log(`Checking if the directory ${newDirectory} needs to be removed...`);
    await deleteFolderRecursive(newDirectory);
    console.log(`${newDirectory} has been removed, if it existed.`);

    // Proceed with cloning and replacing
    await cloneAndReplace(sourceDir, newDirectory);
    console.log('Clone and replace operation completed.');
}

main();