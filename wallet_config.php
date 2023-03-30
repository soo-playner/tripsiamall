<?php
/*
 * @brief 테스트넷 사용여부
 * 테스트넷 사용여부에 따라서 다른 설정이 변경될 수 있습니다.
 * 이를 위해서 다른 설정들은 메인넷과 테스트넷의 설정을 각기 해야합니다.
 */
define('TESTNET', false);

/*
 * @brief BITGO API AccessKey
 * Bitgo에서 받은 API AccessKey를 기록합니다.
 * API Key를 생성할때에 반드시 "Lifetime Spending Limits" 항목에서 BTC, TBTC부분에 값을 넣어야합니다.
 * 최대한 큰 수를 넣어두면 API AccessKey를 변경야해할 시점을 늦출 수 있습니다.
 */
//soo define('BITGO_MAINNET_ACCESS_KEY', 'v2x4e5d393fda5edf9ba661f621a7bf0fa1f0bc5d7c75a581ae447336cf3f23d0e3');
define('BITGO_MAINNET_ACCESS_KEY', 'v2xeb050566350a81f6ddbc6dc1744a0f22ac6953cf18f9f52491a791fddddf20da');
define('BITGO_TESTNET_ACCESS_KEY', 'v2xb224265b93dafaf8b8eda85c9a72fca7798fc63e2b3838c414e18e75d50c4032');
define('BITGO_ACCESS_KEY', TESTNET ? BITGO_TESTNET_ACCESS_KEY:BITGO_MAINNET_ACCESS_KEY);

/*
 * @brief 회사의 Bitcoin Address
 * 회사의 Bitcoin Address를 입력해둡니다. 이왕이면 P2SH(주소시작이 3/2로 시작) 주소로 만드는 것이 Transaction 사이즈를 줄일 수가 있습니다.
 * 회사계정에서 외부로 출금기능 역시 Bitgo를 이용하고자 한다면 반드시 Bitgo에서 생성한 계정의 주소여야합니다.
 */
//soo define('COMPANY_MAINNET_ADDRESS', '3BVApu4xi9n5HS7UhnT7FG5yXYPb5TGP6p');
define('COMPANY_MAINNET_ADDRESS', '3AZmf43A8hrz6DosptgzNbPxYwhbdyQUJn');
define('COMPANY_TESTNET_ADDRESS', '2NFxxfEbmKsNSzU6yDGdaZJ5ycLrgUp7AKs');
define('COMPANY_ADDRESS', TESTNET ? COMPANY_TESTNET_ADDRESS:COMPANY_MAINNET_ADDRESS);

/*
 * @brief Webhook 주소
 */
define('WEBHOOK_MAINNET_URL', 'http://v7wallet.com/webhook.php');
define('WEBHOOK_TESTNET_URL', 'http://seven.willsoft.kr/webhook.php');
define('WEBHOOK_URL', TESTNET ? WEBHOOK_TESTNET_URL:WEBHOOK_MAINNET_URL);

/*
 * @brief 사용하고자하는 Coin의 종류
 */
define('COIN', TESTNET ? 'tbtc':'btc');

/*
 * @brief 입금 처리의 최소한의 양 (사토시)
 * 입금완료후 Token으로 환전할 코인의 갯수를 설정합니다.
 * 너무 작은 양은 회사계정으로 송금시 사용할 수수료때문에 의미가 없습니다.
 */
define('MINIMUM_BALANCE', 2000);

/*
 * @brief 비트코인과 토큰의 환전비율
 * 비트코인 입금시 환전해줄 토큰의 갯수를 정의합니다.
 * 비트코인의 단위는 사토시이며 1 Btc = 100,000,000 Satoshi 입니다.
 */
define('SATOSHI_PER_SEVEN', 1);
