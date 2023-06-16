const { Client, GatewayIntentBits, REST, Routes } = require('discord.js');
const mysql = require('mysql');
const config = require('./config.json');

const connection = mysql.createConnection({
  host: '127.0.0.1',
  user: 'ayd1ndemirci',
  password: 'Vs2H[iM2(QB&g)M',
  database: 'ban',
  port: 3306
});

connection.connect((err) => {
  if (err) {
    console.error('MySQL bağlanamadı:', err.stack);
    return;
  }
  console.log('MySQL Bağlandı.');
});

const client = new Client({
  intents: [
    GatewayIntentBits.Guilds,
    GatewayIntentBits.GuildMessages,
    GatewayIntentBits.GuildMembers,
    GatewayIntentBits.MessageContent
  ]
});

const commands = [
  {
    name: 'bansorgu',
    description: 'Ban sorgu komutu',
    options: [
      {
        name: 'oyuncu-adi',
        description: 'Sorgu yapacağın oyuncunun adını gir.',
        type: 3,
        required: true
      }
    ]
  }
];

const rest = new REST({ version: '10' }).setToken(config.token);

(async () => {
    try {
      await rest.put(Routes.applicationCommands(config.clientID), { body: commands });
      console.log("Komutlar yükleniyor...");
      console.log("Bot aktif!");
    } catch (error) {
      console.error(error);
    }
  })();
client.on('interactionCreate', async (interaction) => {
  if (interaction.isCommand()) {
    if (interaction.commandName === 'bansorgu') {
      const playerName = interaction.options.getString('oyuncu-adi');
      try {
        const query = 'SELECT * FROM ban WHERE playerName = ?';
        connection.query(query, [playerName], (err, results) => {
          if (err) {
            console.error('MySQL sorgusu başarısız oldu:', err.stack);
            return;
          }
          if (results.length > 0) {
            const banRecord = results[0];
            const adminName = banRecord.adminName;
            const reason = banRecord.reason;
            const time = banRecord.time;
            const randomColor = Math.floor(Math.random() * 16777215).toString(16);
            var date = new Date(time * 1000);
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            var hours = date.getHours();
            var minutes = date.getMinutes();
            const formattedDate = time === "SINIRSIZ" ? "Sınırsız" : day + '.' + month + '.' + year;
            const formattedTime = time === "SINIRSIZ" ? "Uzaklaştırılmış" : hours + ':' + (minutes < 10 ? '0' : '') + minutes;
            
            const embed = {
              title: "Ban Sorgu",
              description: `Oyuncu: ${playerName}\nYetkili: ${adminName}\nSebep: ${reason}\nSüre: ${formattedDate} ${formattedTime}`,
              color: parseInt(randomColor, 16),
            };
            interaction.reply({embeds: [embed], ephemeral: true})
          } else {
            interaction.reply('Oyuncunun uzaklaştırılması yok.');
          }
        });
      } catch (error) {
        console.log('Hata:', error);
      }
    }
  }
});

client.login(config.token);
