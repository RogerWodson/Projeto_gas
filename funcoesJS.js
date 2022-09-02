// buscar cep
// tras as variaveis que foram listadas no imput do html, definidas como ID
const form = document.getElementById("form");
const username = document.getElementById("nome");
const cpf_cnpj = document.getElementById("cpf_cnpj");
const NumTelefone = document.getElementById("numtelefone ");
const NumCelular = document.getElementById("numcelular");
const nuncep = document.getElementById("nuncep");
const nuncasa = document.getElementById("nuncasa");

let cep = document.querySelector('#numcep');
let endereco = document.querySelector('#endereco');
let bairro = document.querySelector('#bairro');
let cidade = document.querySelector('#cidade');
let uf = document.querySelector('#uf');
cep.value = '';
cep.addEventListener('blur', function(e){
  let nuncep = e.target.value;
  let script = document.createElement('script');
  script.src = 'https://viacep.com.br/ws/'+ nuncep + '/json/?callback=popularform';
  document.body.appendChild(script);
})

function popularform(resposta){
  if('erro' in resposta){
    alert('Cep não encontrado');
    return
  }

  endereco.value = resposta.logradouro;
  bairro.value = resposta.bairro;
  cidade.value = resposta.localidade;
  uf.value = resposta.uf;
}
// ----x-----x-----x---


