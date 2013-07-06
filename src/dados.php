<?php
$app['title'] = "Tribo Interactive";
$app['dados'] = [
    "nome"=>"Tribo Interactive",
    "slogan"=>"slogan", // chave do locale
    "local"=>[
        "headquarter"=>[
            "endereco"=>"Rua Luigi Galvani, 70 - 13º",
            "cidade"=>"São Paulo",
            "estado"=>"SP",
            "cep"=>"04575-020",
            "telefone"=>"+55 11 55011351",
            "negocios"=>[
                "nome"=>"Leda Cichello",
                "email"=>"leda.cichello@tribointeractive.com.br",
            ],
        ],
        "filial"=>[
            "endereco"=>"Av. Beira Mar, 262 - Sala 802",
            "cidade"=>"Rio de Janeiro",
            "estado"=>"RJ",
            "cep"=>"20021-060",
            "telefone"=>"+55 21 38149650",
            "negocios"=>[
                "nome"=>"Ana Masagão",
                "email"=>"ana.masagao@tribointeractive.com.br",
            ],
        ]
    ],
];

// Twitter
$app['twitter.key'] = '42T8fw5GWeqLesWQ3wNksA';
$app['twitter.secret'] = 'x05yNCG61V6MeycrZ5GVFAmkFkj28V99DuCrIvcjl8';
$app['twitter.request_token'] = 'https://api.twitter.com/oauth/request_token';
$app['twitter.authorize_url'] = 'https://api.twitter.com/oauth/authorize';
$app['twitter.access_token_url'] = 'https://api.twitter.com/oauth/access_token';
$app['twitter.callback_url'] = 'http://www.tribointeractive.com.br/twitter/oauth-callback';
$app['twitter.access_token'] = '44358342-y48yiWS6Qf8t0CS0cylJrc25Jot03rxwctSJ0u0ax';
$app['twitter.access_token_secret'] = 'fCqBSzFLolldtPrzVpuBNWfDzGUpUJEGXC6mIQEnak';
