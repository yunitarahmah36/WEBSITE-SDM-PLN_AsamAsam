const fs = require('fs');
let content = fs.readFileSync('app/Config/Routes.php', 'utf8');

const regex = /\$routes->group\('application', \['filter' => 'role:admin'\], function \(\$app\) \{\r?\n\s+\$app->delete\('destroy\/\(:num\)', 'Application::destroy\/\$1'\);\r?\n\s+\$app->post\('status\/update', 'Application::update'\);\r?\n\}\);\r?\n/g;

content = content.replace(regex, '');
fs.writeFileSync('app/Config/Routes.php', content);
console.log("Routes fixed locally.");
