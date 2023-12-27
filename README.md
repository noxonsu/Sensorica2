# Sensorica2
Create AI tools in seconds . Экспериментальный репо, более универсальная архитектура для продукта который охватит больше AI юзкейсов, т.е. можно запрашивать выше цену.

Фронтенд берем с https://github.com/noxonsu/Sensorica/tree/master/server , вместо промптов будут тулзы. Например chatgpt in teelegram (aigram), или Mascot for the web site (Бывш chate), или OutReach agent (бот ходит по сайтам и собирает контакты - GDPR ready). Для каждой тулзы нужен свой набор инпутов, например для aigram это cf api и промпт. и тп. Конфиг расположен в tools.json по аналогии с networks.json в бч продуктах. 

# Плюсы
- больше юзкейсов - выше цена
- нет размытия бренда. все они построены вокруг запросов к chatgpt и являются ии. 
- sensorica двольно универсальное название
- готовый фронтенд под новые тулзы, которых будет очень много по мере того как ИИ в целом будет развиваться. Новые тулзы надо продавать как плагины, пример  https://codecanyon.net/item/ai-chat-for-whatsapp-plugin-for-whatsbox/49487111
- разный стек. часть тулз работает на github actions и на питоне (smartTokenList или tenders), часть в cf 

# Минусы
- монорепо для
- больше поддержки, т.к. мнгого кода подтягивается чужого
- проблема легаси после года, т.к. апдейты по идее должны быть беслпатны, а продажи могут упасть

# Admin flow
- админ выбирает кейс
- вводит необходимые данные (см tools.json поля inputs)
- данные отправляются на бэк см backendSchema
- админ получает шорткод для ставки результата в любое место сайта, например для aigram это будет ссылка на бота  

# contribution
1. Use pullrequests
2. check tools.json and folder tools
3. escape all inputs usong esc_attr and esc_html 

1. for github codespaces and vscode use https://marketplace.visualstudio.com/items?itemName=lukasz-wronski.ftp-sync&ssr=false#review-details this extention to sync plugin due to development with remote wp-content/plugins/Sensorica2 folder for live preview. 

# devops
1. автообновления пока нет, просто пакуем в зип и заливаем как плагин в вордпресс админке или по каналам типа кодканьона

# English 
Description:

Welcome to the WordPress GPT Integration Suite, a comprehensive collection of powerful tools designed to enhance your WordPress experience using the latest advancements in GPT technology. This all-in-one repository contains:

Telegram Bot (Aigram) - Seamlessly integrate ChatGPT with Telegram to deploy interactive bots, providing a unique way to engage with your audience through messaging.

Website Mascot (Mascot Widget) - Bring your website to life with an interactive mascot. This tool boosts user engagement and conversion rates by enabling visitors to interact with a charming, AI-powered mascot.

Business Developer (Website Outreach) - Expand your network and find new partners efficiently. This tool automates outreach to a curated list of websites, presenting your proposals in a GDPR-compliant manner.

Market Research Tool - Harness the power of GPT for in-depth market research. This tool excels in keyword analysis, competitive benchmarking, data crawling, and generating detailed reports, giving you valuable insights to stay ahead in your market.

Each tool in this suite is crafted to provide a unique solution, leveraging the capabilities of GPT to enhance user experience, outreach, and market research, all within your WordPress environment. Whether you're looking to engage with your audience, expand your business network, or gain in-depth market insights, the WordPress GPT Integration Suite is your go-to resource.