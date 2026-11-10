import { StrictMode, useState } from 'react'
import { createRoot } from 'react-dom/client'
import { ArrowUpRight, Bell, CalendarClock, Check, ChevronDown, CircleDollarSign, FileText, LayoutDashboard, Menu, Moon, Plus, ReceiptText, Search, Sun, Users, X } from 'lucide-react'
import './styles.css'

const currency = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' })

function Logo() {
  return <a className="brand" href="#inicio" aria-label="Curupira Legal, início"><img src="/brand/curupira-mark.svg" alt="" /><span>curupira<strong>legal</strong></span></a>
}

function App() {
  const [dark, setDark] = useState(false)
  const [menuOpen, setMenuOpen] = useState(false)
  const [notice, setNotice] = useState(true)
  const monthRevenue = 28450
  return <div className={dark ? 'app dark' : 'app'} id="inicio">
    <aside className={menuOpen ? 'sidebar open' : 'sidebar'}>
      <div className="sidebar-top"><Logo /><button className="icon-button mobile-only" aria-label="Fechar menu" onClick={() => setMenuOpen(false)}><X size={20} /></button></div>
      <p className="eyebrow">Visão geral</p>
      <nav aria-label="Navegação principal">
        <a className="nav-link active" href="#dashboard"><LayoutDashboard size={18} /> Dashboard</a>
        <a className="nav-link" href="#contratos"><FileText size={18} /> Contratos <span className="nav-count">12</span></a>
        <a className="nav-link" href="#clientes"><Users size={18} /> Clientes</a>
        <a className="nav-link" href="#cobrancas"><ReceiptText size={18} /> Cobranças</a>
        <a className="nav-link" href="#financeiro"><CircleDollarSign size={18} /> Financeiro</a>
      </nav>
      <div className="sidebar-bottom"><div className="profile"><div className="avatar">RM</div><div><strong>Rafael Mendes</strong><span>Administrador</span></div><ChevronDown size={16} /></div></div>
    </aside>
    <main className="main">
      <header className="topbar"><button className="icon-button mobile-only" aria-label="Abrir menu" onClick={() => setMenuOpen(true)}><Menu size={21} /></button><div className="search"><Search size={17} /><input aria-label="Buscar" placeholder="Buscar contratos, clientes..." /></div><div className="top-actions"><button className="icon-button" aria-label="Alternar tema" onClick={() => setDark(!dark)}>{dark ? <Sun size={19} /> : <Moon size={19} />}</button><button className="icon-button has-dot" aria-label="Notificações"><Bell size={19} /></button></div></header>
      <div className="content">
        {notice && <div className="notice"><div className="notice-icon"><CalendarClock size={19} /></div><div><strong>3 contratos vencem nos próximos 30 dias</strong><p>Revise as condições de renovação para não perder nenhum prazo.</p></div><a href="#contratos">Ver contratos <ArrowUpRight size={15} /></a><button className="dismiss" aria-label="Fechar aviso" onClick={() => setNotice(false)}><X size={16} /></button></div>}
        <section className="welcome"><div><p className="eyebrow">Terça-feira, 10 de novembro de 2026</p><h1>Bom dia, Rafael <span>✦</span></h1><p className="lead">Aqui está o resumo da sua operação hoje.</p></div><button className="primary"><Plus size={17} /> Nova cobrança</button></section>
        <section className="metrics" aria-label="Indicadores principais">
          <Metric label="Receita no mês" value={currency.format(monthRevenue)} trend="18,4%" note="vs. mês anterior" positive />
          <Metric label="A receber" value={currency.format(12800)} trend="4 títulos" note="em aberto" />
          <Metric label="Horas aprovadas" value="142h 30min" trend="+12,5%" note="vs. mês anterior" positive />
          <Metric label="Contratos ativos" value="12" trend="3" note="renovam em breve" alert />
        </section>
        <section className="grid-two"><article className="panel chart-panel"><div className="panel-heading"><div><p className="eyebrow">Desempenho</p><h2>Receita mensal</h2></div><button className="select-button">Últimos 6 meses <ChevronDown size={15} /></button></div><div className="chart"><div className="y-labels"><span>R$ 30k</span><span>R$ 20k</span><span>R$ 10k</span><span>R$ 0</span></div><div className="chart-area"><div className="grid-lines"><i></i><i></i><i></i><i></i></div><svg viewBox="0 0 600 220" role="img" aria-label="Gráfico de receita mensal"><defs><linearGradient id="fill" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stopColor="#d86b43" stopOpacity=".3"/><stop offset="1" stopColor="#d86b43" stopOpacity="0"/></linearGradient></defs><path d="M0 170 C40 150, 60 156, 100 140 S170 135, 200 120 S270 148, 300 115 S360 84, 400 105 S470 65, 510 75 S570 38, 600 48 L600 220 L0 220Z" fill="url(#fill)"/><path d="M0 170 C40 150, 60 156, 100 140 S170 135, 200 120 S270 148, 300 115 S360 84, 400 105 S470 65, 510 75 S570 38, 600 48" fill="none" stroke="#d86b43" strokeWidth="3" strokeLinecap="round" /></svg><div className="x-labels"><span>Jun</span><span>Jul</span><span>Ago</span><span>Set</span><span>Out</span><span>Nov</span></div></div></div></article><article className="panel"><div className="panel-heading"><div><p className="eyebrow">Atenção</p><h2>Próximos vencimentos</h2></div><a className="text-link" href="#contratos">Ver todos <ArrowUpRight size={15} /></a></div><div className="deadline-list"><Deadline date="18 NOV" title="TechFlow Solutions" meta="Contrato mensal · R$ 6.000,00" color="coral" /><Deadline date="22 NOV" title="Maré Digital" meta="Renovação · R$ 3.800,00" color="gold" /><Deadline date="04 DEZ" title="Nuvem Clara LTDA" meta="Contrato anual · R$ 12.400,00" color="green" /></div></article></section>
        <section className="panel activity"><div className="panel-heading"><div><p className="eyebrow">Acompanhamento</p><h2>Atividade recente</h2></div><a className="text-link" href="#atividade">Ver histórico <ArrowUpRight size={15} /></a></div><div className="table-wrap"><table><thead><tr><th>Descrição</th><th>Cliente</th><th>Data</th><th>Valor</th><th>Status</th></tr></thead><tbody><tr><td><span className="table-icon invoice"><ReceiptText size={16} /></span><strong>NFSe #2026-0118</strong></td><td>TechFlow Solutions</td><td>Hoje, 09:42</td><td>{currency.format(6000)}</td><td><span className="status paid"><Check size={13} /> Autorizada</span></td></tr><tr><td><span className="table-icon contract"><FileText size={16} /></span><strong>Contrato atualizado</strong></td><td>Maré Digital</td><td>Ontem, 16:20</td><td>—</td><td><span className="status neutral">Revisão</span></td></tr></tbody></table></div></section>
      </div>
    </main>
  </div>
}

function Metric({ label, value, trend, note, positive, alert }: { label: string; value: string; trend: string; note: string; positive?: boolean; alert?: boolean }) { return <article className="metric"><p>{label}</p><strong>{value}</strong><div><span className={positive ? 'trend positive' : alert ? 'trend alert' : 'trend'}>{trend}</span><span>{note}</span></div></article> }
function Deadline({ date, title, meta, color }: { date: string; title: string; meta: string; color: string }) { return <div className="deadline"><div className={`date-box ${color}`}><strong>{date.split(' ')[0]}</strong><span>{date.split(' ')[1]}</span></div><div><strong>{title}</strong><span>{meta}</span></div><ArrowUpRight size={16} /></div> }

createRoot(document.getElementById('root')!).render(<StrictMode><App /></StrictMode>)
