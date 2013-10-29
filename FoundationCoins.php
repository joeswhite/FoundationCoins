<?php
/*
*FoundationCoins.php
*This is a script that queries http://cryptocoinexplorer.com:4750 to get the latest
*updates on the progression of funds given to the FreiCoin Foundation (http://freicoin.org)
*This script is placed in public domain. You must keep all lines in this header as they are
*If you use this for a webpage, please make sure to give the developer credit
*
*This code will also show you the total number of coins on the network
*and the current number of coins in end user circulation
*(mined coins plus coins distributed by the foundation)
*
*WARNING!!!! THIS SCRIPT DOES NOT ACCOUNT FOR DEMURRAGE ON EXISTING COINS.
*THIS IS FOR REOUGHT ESTIMATES ONLY
*
*BE AWARE THAT FREICOIN IS NOT BITCOIN! NOR DO THE FREICOIN DEVELOPERS KEEP ANY FOUNDATION FUNDS FOR THEMSELVES!
*CHECK http://freicoin.org FOR ANY NEW UPDATES
*
*---Output---
*Current TimeStamp
*Current Block Count:
*Total Coins on Block Chain:
*Total Coins in Foundation control:
*Total Coins Yet to be Distributed to the Foundation:
*Total Coins Left to Mine:
*Total Coins in End User Circulation:
*Total Coins Left to Mine on the Block Chain:
*
*FoundationCoins.php
*Script version 1.0 
*
*Developed by Joseph S. White joesfreicoinpool -at- gmail -dot- com
*FRC donation address 1FRCJoeWXbYe47cmuW3do8VoqAr9HuWbpJ
*Released on 10/29/13
*
*Block explorer http://cryptocoinexplorer.com:4750/ is used. Please donate to them as well
*FRC donation address for cryptocoinexplorer: 1CRypTosugXcXQNjp16Cra83c3M24vVW9Y
*
*use this script via commandline php. It should work with a web page as is. 
*Or part of a include library
*
*Run via this command
*php5 FoundationCoins.php
*/

/* This is the total number of coins ever to be placed in circulation (100 million)*/
$totalCoinsEver = 100000000;

/* This is the total number of coins the Foundation will control (80 million) */
$totalCoinsFoundation = 80000000;

/*
* This means that the miners will get 20 million coins during the first 3 years
* so we can set the variable two ways, either subtracting coins for the foundation
* from the total coins to be placed in to circulation.
* Which looks like this
*
* $totalCoinsMiners = $totalCoinsEver - $totalCoinsFoundation;
*
* Or we can simply set it to 20 million
*/
$totalCoinsMiners = 20000000;

/* These are all the known Foundation addresses, each address will have 250k frc at 320 addresses, this is 80million coins */
$allFoundationAddresses = array(
"1DCyWRmTXB9goqA4Zb88nU1Q8snA7d7n4x",
"1LoFvV5YJsSMkpyPLizqyWH8KAkevV2XwJ",
"1JTUD2rB3FvbNFPw7cvCdTVDM9nuZTw7Jk",
"18w4xQQj2iXwtq9smYkEAJrWVz4jQNU4xd",
"16vdGLyxdYgSCT9xAng9Js7KrsnrUHsyG2",
"1Lo8mmskrLnvCuthadVaRS4K7WUSFpWAwj",
"1J1irQQ3ZWoTPct989Nnzdtu6WjfCjQcWs",
"1MME2u4V2ZiU6uUVJXTZMg5sQXAyMBUNXt",
"1CT3kUDi3rvma8R7Jwbz7puATSU3xzfLHz",
"1CLupi58K9XHVeWZ8jwbWiY4Ns46mPALbe",
"16A8XoWWvtJrDE1AdYQoLxAQcoLQML9gjz",
"1NwgZoUnudfmbQ99xDRdvrYskgjQ7KBt1Z",
"17CDPam7M59JM6vK5xzh1vUGKjYT9Byi5S",
"1PyKZKfquWcu3PFzKbvmKZ2oJWXbmbsWdB",
"186LbdeaDsn4Y5zrLN9cfSHWpQPSHtLbgC",
"1MrQWWNKfVseYyGkyyLsDhFekJWGJNt2i9",
"1EAUtv6YfvcRUrU5SncdZ27aSJ6SBNJH67",
"1DuSbRKB1GL9cBeJLYsuh3DADdwJgvHAQN",
"1MPdcnGXHsjR6rFSBUMm4ui44q8Ra1fYRT",
"1Ntv6bDFj8eQnXjawcatnJjJTowo1BA8rF",
"14j9vnqn6FZwPZmwdvGSuESm1m3oQsHP5y",
"1C679HkKyki9rN8tJvtMNyXGLedPdo8zbb",
"1EMKZYHTcnpHVUJx4dUp5Jne2ePQKjpdTm",
"1PmgFAV835znVpUwGkLkvJrKc4ZzBqixNX",
"16zKbgjQDqua6xjrXLhCbPGFrpr8UJxf4x",
"1KPurbuUH5D6HRe3Y148kUbRjDyFCCm3VH",
"1GVyWAXxP9tgZbj8iDSQqQ5tcN36uJ3F1s",
"1E5udyBXuBt1e8c2R27AvSTdp8H7LEhmxr",
"1hQcLTTD7KiFxiojvSrrrj8Y1w2gF5bHE",
"17BJ1oZdZJS64curVAL6rN1yYN7YiNVXpR",
"1LotiV7qGfAZhVV36XtrixnEfHCiuqe39e",
"1Q5yedqC3adLpNjbY4CWMxPojoxnSCVGjw",
"1FpBGhBWn7WDZr9nP47qG3DktJbaY7P48P",
"1H6Nh8dRPZjMm3KViuW5ZESjRwqYnQ36nt",
"1NAAKtpk7VRRUtA5ja8YxCZQaisXQ28HqA",
"1JKxed9uYfvcPgjGdo1GQXwMQJkAnap34G",
"1DZ58aSGD13QfUa118rtvfKrJiVPAoxdV8",
"12wnNuaQHbLyThJVJvfePhV8UwQEWURLLP",
"16f8S6f6ZDX3N1JG2DL5kyz9KCzmwpGgt7",
"1PPKwAUZ6g5wWiopfyJKJZn3xUFcrJbSBF",
"18DzCPRpU1Y2o5FsuuvcScZaYSi2ZBTVFr",
"1E5fy7csgbN5G9ENRwvSwGSAibLdLk52pe",
"1Dapd3WLAz1jm91FpNThHamXeMjDU4TJgJ",
"15HQuReQzSQ1mrHWy3iYELyJLjGNe9gNEZ",
"1DcJhNQJLkDrSmrvATciEaf95ZvnhFFUF7",
"1ErNVYRnGQpzFmxkXYnqR4LbcCViby7Rfi",
"1D1CmGn3BCM5rviTxZEfc7NhozAetePkit",
"1Af3dbEWMK5VuMkUozepYPQgMeVtmKtvW9",
"1JY2W5m4jsYzY2YYXU6RRKDmobE3BYEbgA",
"1PdTBBm2xhCoUY4A6cfYCopaFDsFyTf4MY",
"1Fe51wUzrhyGmag9UXmzEsr6jSyWqcATAM",
"1kyb1A5jWYP49YTkoN2y3JFQuNp1S2gXa",
"1FxZ7fmDQmauMASYVuVcHeajGZQKrQ1UWB",
"1EwtDpNLPmUZNLFmGMmNTwviUVe3DuTFKt",
"1NYRPya8KWUfiSr8fXxccPoDMmBw2Uqj1y",
"16vQMSBZK7iy5HDFfeiP2WomfpGfSEPJx5",
"12E9bCLYb9uzh2MHhpsyR89V3eLXZp5afr",
"1EA4NJjMXSgVNsNgEc7nSyRf3epjp3ABrQ",
"1NN442B74LAsXUMUFZSriWZCUh8b5ECFR9",
"1EMaEQmjjDCjgu3auEam5ABQ1J9ZtdLdpV",
"1RYXoGz2cHTGsYC5zZdDwpCdGRj4aBdAX",
"19aDWt7kBf53uLANiWnLFnWo5CqASh79mi",
"1LDEniSxXknXLHT1BMWpFsBM3PQcgn1nYz",
"1Q4Lji94eWCC9xBzwrbRE9yTMYS5fdKg9z",
"16fQVYur5CVMq9VfNLYypKXNeTmvWnDKsz",
"1Mc3r8pCpuRiHhkD3DrWf89CUnZb6xbFbg",
"18oEnf5iR9CD2HFDc9Yr8kD7m5CrJVWRkv",
"12VDq99L8UQWr8Waqo4GreEGCEBnkxMaXy",
"1H7PxhMmvqiRT8NDEkSFjfDekRLQ65CqBN",
"17yC59RcpYsw7jX3Zw7c48AcWtJqaHUwAr",
"1AFT16ksWdqdjhk56gFDaRnr7vS4XCVtyQ",
"1G84MZVqN54QTD47YWWmimy9htaj1WC58U",
"13YcisL6YyUG5nqegyqyrL6pVtrMqGYtcq",
"1NYdmagVHfbqTgW4hYJKS2YnWrJzCnSsvZ",
"1PumqgHPLUjPKfddgwJA46D5GBdYgT8myg",
"1PFKxU6g1kQayDwvpiLX2vJgUghMqJz9Ck",
"154ENKy3HuYoN8xARVaxp61NUAt5GEknDj",
"12CJ8BD8L8tQXjrpy4UfjJwCCtoL6vsegD",
"1LXCWYJ6k7EG2Bi8rLh2jhV94L4G768yTa",
"1K1rbcUFmE7XScTsqiNEiJHyX69eqbZdDB",
"1uZZzXiu8n7eL96rcFWh9MvcqerYxaGce",
"1JidqtE1YHwXFC1utxPAp17RkM3rUqwULk",
"1PuMwPqNLLYi1sPxvJToid2EsfiP4xPfo6",
"14ZSJRvSdgYFA1xUM2txnQKdMXMfsEWvuJ",
"1D9RJw7p5zgz4JeWvVzYxBsAkvucRMiXfG",
"1JRpRLZgcfNNeVEwGQmYZw5nv7Aq3KVx5x",
"17Rqyx39YnpFN23dPE3CWRPC8JhuBVKktx",
"19pozj4JeWd6rpMDeTpx8d1Dv4rebhUkvT",
"15jULtTPTzXHr9ezTMFbaPJojbuYFrbrQp",
"19dfCSTERPh5j4XtYoJatjdjD9afReeY3s",
"1LgzNc1Sfbu8BaxKUESGbNzCNnpqvhpCi4",
"1HTvoZUUNncPkjjv17xHLEtncdrgcdnN46",
"1NHvSZWwk8RtgPvfhzykpvebQnVk1Q5XxX",
"1AzdeDfjz5C5yT6wVxurgS8QPkZviHvY8N",
"1BdFwnfS84uDeZn4sojUs5ZC8fSkx9o2XG",
"1AgCAgvQZPQTkdMg853SkM2WdRzN4Q2ATw",
"1JABYERsgkAYincsgCpic7MwV63iM19iXp",
"1JFudqZDUkBMdV4ShLmhxLD7sfNEYdBQCE",
"1Pqf48Skyxt77RNVwTLxUhA2BNCscaHJKa",
"1AtdTwFFYZJrUUSWbBLBCkodRcnqwb1a6G",
"14iezrH1nR9TjGtnywFPqBHbwYcEhwz8y9",
"16x2aavFb2AHKntUnzA3HC2wmi921YJn4i",
"1HovjtiToM6f2xV3Sxg4fxfvSYPCGGEXLe",
"1MNrqZyo7poywLPVap6PsmmT5CS4f8hyWq",
"1F6PzQRW2MPfCYvzgeUXoBXaEikH3E5zMk",
"1F2SpgUakBvx6aNgJiCtEZHnTqVWeQcoMk",
"13iTRwxSLGC17fzumSrRidaXe8v8awdDux",
"1KuyBiZBdXVq8oNGAPWEqWiFi2RyH8rvwd",
"1HdXmhHKkkzpn1UKmhBWFzQMYsUqxUuVZ9",
"1Dw9jXoWc5MsEH3uLB9pi98qeyijUrvWU3",
"15mW5WsusPo6LAAYLqa6ngFfQ1jX51v3Bn",
"1DFfarcjskvSi2w56msV4JeeVZqtuwEL9p",
"12SeGWd2txi4fdQKoFXsTdd2fgjDbABWyb",
"1MoENmjtakS8XTHcwsbVFeJkjEckMhS3xm",
"167pv4Hn53XQ4hFhyNtEyP36n8HrL3NU3j",
"1E6WgpC4bmYJagvsTzhRxZ1Z8sRSsQjmJX",
"1EFkVCzezsZCq56JWSBRf3Dy6tafFRxh4N",
"1KnKZwDb44Qf3Lutda2T85uFZiTZwe2v2C",
"1CLpF2fLukzBHard43mXLEXxz11gFK5dc9",
"1DXSfPi1Tj6tQ5qf5M6Yj6cpNmLfPKMwr1",
"16nHP74UsqeHewM1yUhNCL3zCjkWnqFt8g",
"1FeqXkG9jGEDcPaKJV8rdh4NbqTjbdvN4a",
"1LwwjmsoDtQ1Zh9N8doGMczP1TJnes2YoZ",
"1DgusdNgB6nRD2emfwURMmk33LrB7Wp95c",
"17kjPofVVmhZAWXnrVwfqizGtXWBufWwbf",
"1EnLHA3U15wXehXAC24W587EEaeyUcaA6K",
"1MwpkFtEwrAQNbsmbt4kB9WtoB8mFLXZ44",
"12iQRcVoRCbFNvoQARM3rufTkd7jXpHZEm",
"19zK2WFDkaHZfWa4uS5mzF2XD1KrZEMxy2",
"12Zs8LtRY1cTS3HKw1gwPzYjB1Ar6Er93R",
"1KDVcQhjZuX39Fvv8QbrSpaSycMA4YdPkU",
"1AT6rxNBT8sasYKrKm9fv7LdjXBS89Wewh",
"19YjbLEUgqV8joQMgijDWZoY1inwXf1hXc",
"1EpHQ43BkzmKYMiYwmRRKEXQidpgA499px",
"13bQP93mmUFtUGVuBEwZ9ymdbCC9yywgdL",
"1GatPyGkCX5YUW4f2QHJk1PzwspCRz9b3J",
"1Jk8sCUfHVE6VpwkkTG9qaYYS9u1zMmQAs",
"13N4Eiv2KiX4PeFwiWnC847JBv4TP2sn1Z",
"1ESzED9saJ3bVB6BbVSTFGDxRLnTgWRVDC",
"1CspvzG7HyuNXRLsaWnpsLXPDwkeDKd4mm",
"14Rs4fo9tK39kyEFoAjbvkcgGZ6k356t3T",
"1D6jgPJYoFhbY7gJjNMAbyfJzBGVtqSc1o",
"16MHoaVyYQgPU525fz2auJpK6JVyFKEiz1",
"1FfS9TQswYZHYDNkUmncRAYjYJkLzGncp5",
"14PXPSEjNjWAuqYa63RBT6gewnomE9saRu",
"1CHBBtBCRQz1TFyE12g8RbGPZ6UzX2AieC",
"1CMwT1jzfoe9VvURpZanaXVQobMQLr13W8",
"1696KNrMvHvnthPLZnGuYGY96UbEqLeXz6",
"1A7TQi9sMiNQX8uwwqFb8eqaXnpTJY4WYg",
"1GNuX6AN2KCF1AWtxAT9QYD6QRJubRvKaz",
"16L3CvHeZcZcr3wPhoEC3ZsMLN7YTonMTQ",
"1EnqRdqx1VZyfc5ia4pcmZstBcGdW8FGxn",
"1MQ1QeCMZhxFCgReGEPRS2Qy74FaPqFccW",
"16Za6Rn8dCmM8gctXQtwN1yQ2WXnhHsSgs",
"15k38dy86CRnirMY9Q1niVmfn7nfXTmppL",
"1MQsruCXBjCZzTZKKpPwcC74ztetbtAw4E",
"1cmAt63c4ZAqRe2fBQTYs5Jyx41fiBbhQ",
"1PBCaowV7gQM6Lj1NfSpH2TnHHmqXcYTsC",
"1841uXFc2kUTUogCDJwp4U1NPjSPqsg69x",
"1XNo9kDMM6uqvf9yCWmqj17rukC8abjtb",
"1A4kHAe6rNz1q8G6dYjNMyWzgVv4DxYget",
"18Y6y6zcJrG5j2RjmGqsUvtWkZhnTvRka7",
"1DkcMkHWUUVjXgAu2MFXVkUuwZ6JWv64cz",
"1JvXTyBxhjE9mERWEFnqeuAPgbJSi25qGd",
"1FHus2MsM8k4oKHt22YFYeoFkf65kxQFP3",
"18HLkAhrzeNsaMB3MY1xUGW7wkzjWGobT7",
"1AF7KmTRrS3mMxop2Viop1MctrNJmPAHQt",
"13g4rWjU2PK4eN9D9XXo4jRB84RiJ2hD7o",
"1EWUiUoxZXfTbZXDZGueag7XRnv5Mej8ZZ",
"1LuuGk4tyd4USQqtYypemjt5vs3VRqV1QU",
"15eUUDUYDuiKnt9xNbzhNFmorCK9F9mJb2",
"1HGzWgdrNAKsE9nE1GHtUvaXHNzvwTyPQX",
"11MhmCVmFszm6yTTwaK2dypwcLaybmCjp",
"1s9XWpGPQqhbog1S6xgGqcVnfvnLMAueZ",
"16f3tHcuRavx3tSWCM2jnnCX5jGa2vJe9Q",
"15NWaghRx51ravYTUqsnBF2hQFQeSHtTvS",
"1QGUUgikmqCinDQn3vfqx9q6mnT5ekA4BG",
"134WvpvyZUveYc98CmtWZc1oBBXdrV1GuU",
"1LqNfcDBn7eytc7Ln6fLrCDLkYeMa6R9dV",
"14xbponjm6rXp8cNzTJmtCJwvwvDuKvaCD",
"14D7JyUrv1HeSD7FCc8WupmbxUiGyfC7uC",
"1ER6GhDJokhBjB73DWDTdC2BP2J9DiqD1o",
"13Q3or3Hew7hBZzMoriz8LcMXwptqD5HEd",
"1HSyeVQEvdRwj2rutFN33cKu2tPzyGkgx2",
"1A1WaQQ6ZjXuEe2KYZNC3ycPg4X9czsR4D",
"1fhzxkMPY4hUYNywoQwyVGkinVKQrPJ2P",
"1Nf63BqwEmb7vU15bRfpvKEs5tMGZpR5Fi",
"1Gi6tjnRBecQovhRQVNmsPyVZYmphZerdg",
"1AJ87nhgSQkac9BUjEvbyWh8c95ciHLZWG",
"1WDyJLrJaLRePMtea6bAgADwzdpbW5nqd",
"1JTvhcJuxydevXw4ocUUteiPNWwPtMM56H",
"16X1LYmpxM2fPBjNTLbnPo2LdA6sB7fbNu",
"1315ZWhxgd6pqqTmvF21fxt5wzYvpcnZSm",
"17PWpyrUmkaCVPu6KXaWvuLLYvD9YU61RP",
"1PADxQpcx8Kvs3PprjYvM1wYFyjxB3tcs8",
"1BWyJmxybx3p1guhud8qxabrGbVLWaVNaM",
"17JpmLSEbXgmheAvTQ7iiBvR5TaSsM2Xgt",
"19oxMuyyipVsvxXWKBBrFmY8hQbWkiiVEv",
"16TvroBFWJmUN7VSHQLmyh6KiCri5QVTQu",
"1MXJR6XRoThY9rwvyvLkXWN17WN7rAQC4J",
"163N8CmDAf45CM6brXMpzg3AN2nkDXTuRt",
"1CWudCKLCxT5AXteLFeZRBDyb4moQH4cVL",
"1DYmgt2zpW2eNfyczC98aq76URHQMnfwZK",
"1JnE2YseXgBX4oHGo8VywsxnNkp52s6nkX",
"1D8WBBBCHhgLrMa8s3QU1bkRcRHEt8cNfv",
"1Fm5eoDvEZo4hyW4YEDu3q2gKbpCuo9hqw",
"18uRTixnVaKMz9tyoR6Ve6Rqdwtt8oZ1Zw",
"1FuByKdd2RK3hjc3UFeV56HvheyAMnjMMS",
"17nDqatJ7M6M9vFRa4BngCCLPGSJ6mfc8b",
"1G4qHkiaaVZwuLqwvh2itFjR18iThkeaDQ",
"18ZcHUg5wV4sSdd9pS7xv5rYsfx5D1hZWi",
"1CZU6UCZjtWueXQWYzyrFa4K7pTSeBQ8cw",
"19zRVJvXaXZvygqbHAP1ZKF5Rx9gq3Xh8u",
"1HQAyw9UUi2eiQHJcnbg5eeJTnv2QoEQqA",
"16eZAqdqypn47T8DwS1archd39uXqK8JQ8",
"19he5Hy915MbSZBvwHjB3LAm5UyLnmQ5TK",
"1CzGcY5JKDroUtdFdZJArGeEmKMEtyeAKw",
"1DzowkZrtEQgoDF8xgxjPBfLaBMeBHjNr2",
"16GA6zc9iTUB8o47oi7fbE88ayEi8C7w2r",
"1Mvp5TikHrzJetDMbjHkzAkP9rMBfQrais",
"1Lrbk1vrmCqVfajBqtwHD1x9x72jeDCon5",
"1AMDHRKUah3J8yESFt7NnoUXrM4ULHcUpN",
"1MbnTTv5FJX8RsK5tw9KjNx1VCvo94GEKK",
"1Hmbm1TUDuDwdVWkU1oiaReRRBTzb8fMDJ",
"15XYapuYSjaDc4uDXJsf3PF33YzSRs5P3M",
"1C3ovhhZwo73isNQPuKKD5VDm2XwByBkTK",
"16VJrBFjFjhLY93NihDvWqBpUeiXeL2FUi",
"1C9Niuy1cSW6a6g5tm8GhPsSML6ZtWeUQS",
"149937wZtsTvtwmixD33npnsnyUm5zjstX",
"1NkCKjPZUFecVWxLGnJbN7Fp8viJRG5Xg4",
"1MG3okwhF3YDwVWDcYsNr5ySA4eMtCATrK",
"1GtzbwNuHYBZaDRVpJGuDwjBQhSh5RBVRZ",
"1CJi6dja55AtGeuJX6WLFGTHsoofqZyDNu",
"1oftVXkjfpJSMKGnz1pps1xVWNUNNhAmq",
"168KgGGUEEx22eCNuSMjsKvn5chiZ5c217",
"1FmQzGLJFu3AvucwDEAjYRM4fPgiSZsT6Q",
"1F9t4EmWXy2Wui21LaMuZmRDwRCF38aDZN",
"19eFuss1dgxPdDfoAu6AsVmBUj5d2DUPu3",
"15Yr2PPbFGqbi2SZtZ59cvd5y2Es8atRE5",
"1Pz8oisCda5aJXtVVDo1mfxxvgymVNcmsM",
"1HkHQHNkjXp6VinoEG6a1i55NmreXC9yAX",
"12ShPmbsADZMacnr5u2DPxssKXjd3HaCZc",
"1BasQDDfZ677LF3mEAQUEFHvJexZ8ZxY47",
"1FVXTVaK3rwSrx67WGdNkNFwL5sVm81TEK",
"1NV8VjVBrkgCTJvyBHZumboXjPtSNZvRJX",
"1HVdN1BSusJZ42sSfJFHB2CJ6LcW5Fz31a",
"12j2yfUP2dNo3HwdrTDjMGZhzBcdhYvFj6",
"1BrWHBKCpvNYssq8Kj8yY6qvy7GqFgk8UP",
"1G3faUnBxMHwwX2uLn6dZJEj9pmJ2o5cnq",
"1Nq59Py1u73B26aTTRhZG3g9h5fmrmkeX9",
"1DMuz7B193myzVq4Kgg76Jb6Da2UjkAti",
"1124BMmAevhic3H1MQB3teQFhoVi7RVUhR",
"1B1hrgcDNfSuaKJi3oJ4cBtyysq2BpGFz8",
"18NE1w2soK6xGUYYvoTe7oEtRtQhxBLXCq",
"1MESy7CY2yTgxSERyejcvCGjK8Qm2EhE4g",
"1445Hs1Lgh9pPvD8mSt5oiGwTY8yT2sy9R",
"14pm2Fxwin4mwHqd5ujXAXTJJFuQ31qYUf",
"17UhQpeFQ3nCjj8PJKCrTWHnP8YSvrNM7h",
"15zVu5t8iURV2feuvmnHgYp9u7cxPC4XrN",
"19JriYALeNskNnvjYidpoNHNLegftkViqH",
"13mauBB6JYTPcfoWbNbCWKk4sNqmwxCXse",
"1LwRt9rpGaekbht7UAitg2ADmFtDrKThYV",
"15Z9wnxM6VxrRkqhLZpLskGRJ2dLRMEmCg",
"1GvQwfMSMRggmmFCRqf1EmvaG5U5sY4sKL",
"13iUoiiVq7C4fUmy94r1HEDf35YKwBAVXh",
"1ELZsnzgBmZSSxQQYuAANg8izDFTbzhbPB",
"13me2Z71XAtmkzggnqusdvuRiXZzFRGZBj",
"1KC7ECvdcofiYXJ63iUnvFrEH4zzhQZ2pB",
"1LtFLa3oaEBhmHQ5iXRvFqeNcrzU8GPNMM",
"1LNHH8DGXWQRyfmkSkaJcBkkiVxUhH8tBM",
"1Daw5kGzsqBhfRfMV6dAA4bgBZ2LBWS1nY",
"164XLENwRiappRPUP47sRTSyXtW8CAXVLi",
"1CFoTaknkFGADVo2rK92jwq18NWBzVcJGS",
"1MnrNuPrnuJFxYnkpKDqUymHGjb1d6qLVq",
"1BpwPwf8kUssmoMCoWnHCVY4wjBJi3CZyD",
"1jiJb2DU3DB6ujD8eV3DZXmnfwWaHti4y",
"1DqQnvWdtKvwBtePpCbDd8juZ9ZbeaKFdH",
"131D32PNpqqGtLGUaAaZePqpUdBTiy8Akh",
"16jK6KaY7Ub7fZ7YaBi94ZsygovzixnRNx",
"1EJx1ShX4UJVrzynP3oZw8wdLpSGC1KPrz",
"1899kFmma5FongtN9JfvFKqhwtbw2w9MDe",
"1FNNBK9SeDUufbbnmoagUFt7oKVbb65vaw",
"1LxF4pLjeSpNs4ux24MDduzCzrM2KCsE7M",
"1BZjVRe2CCA7G7qnG3beWWhG173f1mbNX2",
"1L5HWs7WrK457CzjAgnHghveFpQVv7rRTe",
"1Kp4bjG9nwbogd8WM62ijGG6onW9Wo4aYK",
"1hhvJ4QmB6RX12Bps9xhnMHCDDXTXAnDu",
"19xiuTYSm85gNsPZw8hGLS69e2DjVbCuAP",
"19WdcJU8Z1W3ZZnbpfDRbdrYGapxp1L5zo",
"121hT7w8DN3x1pYEowak7FjmNgihMNo2cd",
"1BtthjPb9GPKwgcJtrgZRQRWhiRSCHmyvk",
"1JKgRTkMgEodFFpPwoz9W6pejMN3x3J9X1",
"18zp4dHdouYqFn2qC4ttAva8cwqhZ4pm4K",
"1ELAVZKvGykuzRDCvFUsJTL4istYisbxpK",
"1PfULZdJniM8SutFdjoKvG3WLUwxZL2YUf",
"1DTbnuz4dPLdduseE3k5xr62eFAYjCSk3E",
"14jVGdWJRcqpdgWPAbbvhMfVnLha6MdnYU",
"1GBiMVjsqkcGxij2hGFQxVUX2WjDcr1Esf",
"1FGUuAuGRkSqEL8Besg33QsekxmBB75ZUH",
"19FsZeejdbfUKK21wENRdoR2BUowD4FsMZ",
"1LbBHWffmANdhcb1Wciv4jWwXPGrtVFhsU",
"1FT9PRuDmFKxZorYrfgibWaaBdKWv7PiB4",
"1FUvPJ3nXMUrFEWqkjxPe5esqQ2GoCmUAk",
"1LJLBDK8q7yLibK2oYTA6hbD9UpmP6U3QP",
"14E9GEg9T5N9aja1FV2ewNFjMK6wPEgsKb",
"1JYmABbYkUjAyowLwa1zoQj86PEWMBdeZP",
"1NCfTbrEsZrCT3Efyk5AfvqP2xY6NesWHy",
"1Bwd6rcgGLq8sdo3FHHSmh3J7ufqdgMeqi",
"1PzAWHEt2xabgWEki5hTgwtTyuKRS1at39",
"1Fo3r7DWDtJ8Yu2UqngNqKMSw98XgsXehW",
"1H1b6FLd5eqH8Q9Cw8UkZv2nY3xxKTfsH3",
"12x9TqiF9FQU7sqnRiCmrRZmG7dLs9hyG6",
"13VYYQ8K9AFiajev9QdHM6Kj8SqevRT7GS",
"149HFz2K7D4GffQm8t7rKQuWmcwJohsimk",
"1JqwkYTg3ZuWMpjhxrJYgW7E826HYoiBSG",
"1NiyjCKxM33nozwzU2LNtWBPWrWTUpiaAM",
"1LD4F5tA87e7nMwNRuHhgwH6zTFZ1LyoE2",
"1M3wUX9YYrcVSSw6Tncdoic3Fj13okQ63u",
"1PVKsqeVqM4B2ccq915GHeK3aDeruStr24",
"1PKNQqSuPknZ1PaqKkRqa9qYujWKL9KQ7E" );

/* This is the numerical representation of the total number of Foundation controlled addresses (320) */
$totalFoundationAddresses = 320;

/* Now we will query http://cryptocoinexplorer.com:4750 to ask it what block we are on */
$currentBlock = file_get_contents('http://cryptocoinexplorer.com:4750/chain/Freicoin/q/getblockcount');

/* Now we query again to get the total coins in circulation */
$currentCoins = file_get_contents('http://cryptocoinexplorer.com:4750/chain/Freicoin/q/totalbc/$currentBlock');

/* Now we will be a jerk and grab the contents of every last known foundation address and spam it out to our terminal (if you have a web page, be kind to cryptocoinexplorer.com and just rip it to a database. */
$foundationTotalBalance = 0;

/* This variable is used to count the number of used  addresses, and tells us how many are available */
$usedFoundationAddresses = 0;

/* This function takes every one of the 320 addresses that were comprised for freicoin and loops through each address grabbing it's values */
	foreach ($allFoundationAddresses as $recursiveSearch) {

/* This simply is a constructed string where we can get the coins in foundation control the remote block explorer. */
		$getFoundationBalance = file_get_contents('http://cryptocoinexplorer.com:4750/chain/FREICOIN/q/getreceivedbyaddress/'.$recursiveSearch);

/* This section of code omitts empty addresses and only shows you which addresses have coins (and how much each address contains). A Foundation address can have a max of 250k FRC.  */
		if  ($getFoundationBalance > 0) {

			/* This line of code prints out the Foundation address that we are looking up */
			print_r($recursiveSearch);

			/* This is just a way to make it easier for humans to read */
                	print_r(" - ");

			/* This is the spamming of the balance of the address currently being searched */
			print_r(number_format($getFoundationBalance));

			/* This line tells us that it is FRC we are counting not any other currency */
               		print_r("FRC \n");

			/* This line adds the current block's funds to the total number of coins owned by the Foundation */
			$foundationTotalBalance += $getFoundationBalance;

                        /* This is telling us total foundation coins discovered this far */
                	print_r("Total Foundation Coins: ");

                        /* This is the variable that stores the total number of foundation coins discovered this far */
			print_r(number_format($foundationTotalBalance));

			/* Start new line to make results look better */
                	print_r("\n");

			/* creates a running tally of the number of Addresses with non 0 balances */
			$usedFoundationAddresses++;

                        /* This is telling us total foundation addresses used this far */
			print_r("Foundation Addresses with funds: ");

                        /* This is the acutal variable being spammed showing the total toal foundation addresses discovered in use this far */
			print_r($usedFoundationAddresses);


                        /* This is telling us The Foundation has 320 total addresses */
                        print_r(" Out of a total 320 Addresses\n");

			/* Start new line to make results look better */
                        print_r("\n");

		/* Return bracket to close out loop when all addresses with non 0 balances have been tallied */
		}

        /* Return bracket to close out loop when all addresses have been scanned */
	}

/* Start new line to make results look better */
print_r("\n");
/* Start new line to make results look better */
print_r("\n");

/* This time stamp tells us When this information was collected */
$today = date("D M j G:i:s T Y");
print_r($today);

/* Start new line to make results look better */
print_r("\n");

/* This tells the block number that this snapshot was taken at */
print_r("Current Block: ".$currentBlock);

/* Start new line to make results look better */
print_r("\n");

/* Tells us the total coins on the block chain which we asked the exploer for earlier */
print_r("Total Coins on Block Chain: ".number_format($currentCoins));

/* Start new line to make results look better */
print_r("\n");

/* Tells us the total coins under the Control of the Foundation */
print_r("Total Coins in Foundation Control: ".number_format($foundationTotalBalance));

/* Start new line to make results look better */
print_r("\n");


/* Tells us the total number of Foundation addresses with funds. */
print_r("Total Foundation Addresses With Funds: ".$usedFoundationAddresses);

/* Start new line to make results look better */
print_r("\n");

/* This gives us the number of coins left to be given to the foundation */ 
 print_r("Total Coins Yet to be Distributed to the Foundation: ".number_format($totalCoinsFoundation - $foundationTotalBalance));

/* Start new line to make results look better */
print_r("\n");

/* This gives us the number of coins left to be mined before all 100 million coins are reached */
print_r("Total Coins Left to Mine: ".number_format($totalCoinsEver - $currentCoins));

/* Start new line to make results look better */
print_r("\n");


/* this gives us the number of coins in circulation by subtracting the foundation's wallet balance from the current total coins on the network */
print_r("Total Coins in End User Circulation: ". number_format($currentCoins - $foundationTotalBalance));

/* Start new line to make results look better */
print_r("\n");

/* This is the final copyright (or lack there of) announcement. */
/* These lines serve to prove authorship and are requested to remain intact on ALL versions of this script*/
print_r("\n");
print_r("This script is public domain code. This notice must be left intact.");
print_r("\n");
print_r("Script developed by Joseph. S. White joesfreicoinpool -at- gmail (dot) com");
print_r("\n");
print_r("FRC donation address to Joseph S. White: 1FRCJoeWXbYe47cmuW3do8VoqAr9HuWbpJ");
print_r("\n");
print_r("\n");
print_r("Block explorer http://cryptocoinexplorer.com:4750/ is used. Please donate to them as well");
print_r("\n");
print_r("FRC donation address for cryptocoinexplorer: 1CRypTosugXcXQNjp16Cra83c3M24vVW9Y");
print_r("\n");
print_r("\n");

?>
