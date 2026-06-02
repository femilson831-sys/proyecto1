@extends('admin.layouts.app')

@section('title', 'Reservas')
@section('page-title', 'Gestión de Reservas')

@section('content')
<div style="padding:8px 0 40px;">

    {{-- ── FLASH ── --}}
    @if(session('success'))
        <div style="background:rgba(74,222,128,.08); border-left:3px solid #4ade80;
                    border-radius:0 8px 8px 0; padding:11px 18px; margin-bottom:24px;
                    color:#4ade80; font-size:13px; display:flex; align-items:center; gap:10px;">
            <span>&#10003;</span> {{ session('success') }}
        </div>
    @endif

    {{-- ── STATS ── --}}
    @php
        $stats = [
            ['label'=>'Pendientes',  'estado'=>'pendiente',  'color'=>'#f59e0b', 'bg'=>'rgba(245,158,11,.08)'],
            ['label'=>'Confirmadas', 'estado'=>'confirmada', 'color'=>'#4ade80', 'bg'=>'rgba(74,222,128,.08)'],
            ['label'=>'Canceladas',  'estado'=>'cancelada',  'color'=>'#f87171', 'bg'=>'rgba(248,113,113,.08)'],
            ['label'=>'Completadas', 'estado'=>'completada', 'color'=>'#60a5fa', 'bg'=>'rgba(96,165,250,.08)'],
        ];
        $totalIngresos = $reservas->whereIn('estado',['confirmada','completada'])->sum('precio_total');
    @endphp

    <div style="display:grid; grid-template-columns:repeat(4,1fr) 1.3fr; gap:14px; margin-bottom:28px;">

        @foreach($stats as $s)
            <a href="{{ route('admin.reservas.index', array_merge(request()->except('estado','page'), ['estado'=>$s['estado']])) }}"
               style="background:#1a1814; border:1px solid {{ request('estado')===$s['estado'] ? $s['color'].'55' : '#2e2b25' }};
                      border-radius:10px; padding:18px 16px; position:relative; overflow:hidden;
                      text-decoration:none; display:block; transition:border-color .2s;"
               onmouseover="this.style.borderColor='{{ $s['color'] }}66';"
               onmouseout="this.style.borderColor='{{ request('estado')===$s['estado'] ? $s['color'].'55' : '#2e2b25' }}';">
                <div style="width:7px; height:7px; border-radius:50%; background:{{ $s['color'] }};
                             margin-bottom:10px; box-shadow:0 0 7px {{ $s['color'] }};"></div>
                <div style="font-size:30px; font-weight:700; color:{{ $s['color'] }}; line-height:1; margin-bottom:5px;">
                    {{ $reservas->where('estado',$s['estado'])->count() }}
                </div>
                <div style="font-size:11px; color:#7a7060; text-transform:uppercase; letter-spacing:.08em; font-weight:500;">
                    {{ $s['label'] }}
                </div>
                @if(request('estado')===$s['estado'])
                    <div style="position:absolute; top:10px; right:12px; width:6px; height:6px;
                                 border-radius:50%; background:{{ $s['color'] }};"></div>
                @endif
                <div style="position:absolute; right:-10px; bottom:-10px; width:55px; height:55px;
                             border-radius:50%; background:{{ $s['bg'] }};"></div>
            </a>
        @endforeach

        {{-- Card ingresos --}}
        <div style="background:#1a1814; border:1px solid #2e2b25; border-radius:10px;
                    padding:18px 16px; position:relative; overflow:hidden;">
            <div style="font-size:11px; color:#5a4f30; text-transform:uppercase;
                         letter-spacing:.08em; font-weight:600; margin-bottom:10px;">
                Ingresos activos
            </div>
            <div style="font-size:22px; font-weight:700; color:#c9a84c; line-height:1; margin-bottom:5px;">
                Bs {{ number_format($totalIngresos, 2) }}
            </div>
            <div style="font-size:11px; color:#5a4f30;">Confirmadas + Completadas</div>
            <div style="position:absolute; right:-10px; bottom:-10px; width:55px; height:55px;
                         border-radius:50%; background:rgba(201,168,76,.07);"></div>
        </div>

    </div>

    {{-- ── TARJETA PRINCIPAL ── --}}
    <div style="background:#1a1814; border:1px solid #2e2b25; border-radius:12px; overflow:hidden;">

        <div style="height:3px; background:linear-gradient(90deg,#c9a84c,#e8c97a 60%,transparent);"></div>

        {{-- Toolbar --}}
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap;
                    padding:20px 24px 16px; gap:14px; border-bottom:1px solid #23211d;">

            <div>
                <h4 style="margin:0; color:#e8e0d0; font-size:16px; font-weight:600;">
                    Lista de Reservas
                    @if(request('estado'))
                        @php
                            $activeColor = collect($stats)->firstWhere('estado', request('estado'))['color'] ?? '#c9a84c';
                        @endphp
                        <span style="font-size:11px; font-weight:500; color:{{ $activeColor }};
                                      background:{{ $activeColor }}15; border:1px solid {{ $activeColor }}33;
                                      border-radius:20px; padding:2px 10px; margin-left:8px;
                                      vertical-align:middle; text-transform:uppercase; letter-spacing:.06em;">
                            {{ request('estado') }}
                        </span>
                    @endif
                </h4>
                <p style="margin:3px 0 0; font-size:12px; color:#5a4f30;">
                    {{ $reservas->count() }} reserva(s)
                    @if(request('codigo')) · búsqueda: <span style="color:#c9a84c; font-family:monospace;">{{ request('codigo') }}</span> @endif
                </p>
            </div>

            {{-- Buscador + limpiar filtros --}}
            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">

                @if(request('estado') || request('codigo'))
                    <a href="{{ route('admin.reservas.index') }}"
                       style="font-size:12px; color:#7a7060; text-decoration:none; padding:7px 13px;
                              border:1px solid #2e2b25; border-radius:7px; white-space:nowrap;
                              transition:color .15s, border-color .15s;"
                       onmouseover="this.style.color='#e8e0d0';this.style.borderColor='#7a7060';"
                       onmouseout="this.style.color='#7a7060';this.style.borderColor='#2e2b25';">
                        &#10005; Limpiar filtros
                    </a>
                @endif

                <form id="searchForm" method="GET"
                      action="{{ route('admin.reservas.index') }}"
                      style="display:flex; gap:8px;">
                    @if(request('estado'))
                        <input type="hidden" name="estado" value="{{ request('estado') }}">
                    @endif
                    <div style="position:relative;">
                        <input type="text"
                               name="codigo"
                               id="searchInput"
                               value="{{ request('codigo') }}"
                               placeholder="Buscar por código…"
                               style="background:#141210; border:1px solid #2e2b25; border-radius:8px;
                                      padding:8px 34px 8px 13px; color:#e8e0d0; font-size:13px;
                                      width:230px; outline:none; font-family:monospace; letter-spacing:.04em;"
                               onfocus="this.style.borderColor='#c9a84c';this.style.boxShadow='0 0 0 2px rgba(201,168,76,.1)';"
                               onblur="this.style.borderColor='#2e2b25';this.style.boxShadow='none';">
                        @if(request('codigo'))
                            <a href="{{ route('admin.reservas.index', request()->except('codigo','page')) }}"
                               style="position:absolute; right:10px; top:50%; transform:translateY(-50%);
                                      color:#5a4f30; text-decoration:none; font-size:13px; line-height:1;">
                                &#10005;
                            </a>
                        @else
                            <span style="position:absolute; right:11px; top:50%; transform:translateY(-50%);
                                          color:#3a3530; font-size:13px; pointer-events:none;">&#128269;</span>
                        @endif
                    </div>
                    <button type="submit"
                            style="background:rgba(201,168,76,.12); border:1px solid rgba(201,168,76,.35);
                                   border-radius:8px; padding:8px 16px; color:#c9a84c;
                                   font-size:12px; font-weight:600; cursor:pointer; white-space:nowrap;"
                            onmouseover="this.style.background='rgba(201,168,76,.22)';"
                            onmouseout="this.style.background='rgba(201,168,76,.12)';">
                        Buscar
                    </button>
                </form>
            </div>
        </div>

        {{-- Tabla --}}
        <div style="overflow-x:auto; padding:0 24px 24px;">
            <table style="width:100%; border-collapse:collapse; font-size:13px; color:#c8bfae;">

                <thead>
                    <tr style="border-bottom:1px solid #2e2b25;">
                        @foreach(['#','Código','Cliente','Cholet','Fecha','Experiencia','Pers.','Total','Estado','Acciones'] as $th)
                            <th style="padding:12px 12px 10px; text-align:left; font-size:10px;
                                       text-transform:uppercase; letter-spacing:.08em;
                                       color:#5a4f30; font-weight:600; white-space:nowrap;">
                                {{ $th }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                @forelse($reservas as $i => $reserva)

                    <tr style="border-bottom:1px solid #1e1c18; transition:background .12s;"
                        onmouseover="this.style.background='#1e1c18';"
                        onmouseout="this.style.background='transparent';">

                        {{-- # --}}
                        <td style="padding:13px 12px; color:#3a3530; font-size:11px;">
                            {{ $i + 1 }}
                        </td>

                        {{-- Código --}}
                        <td style="padding:13px 12px;">
                            <span style="background:#141210; border:1px solid #2e2b25; border-radius:5px;
                                          padding:3px 9px; font-size:11px; color:#c9a84c;
                                          font-family:monospace; letter-spacing:.05em; font-weight:600;">
                                {{ $reserva->codigo_reserva }}
                            </span>
                        </td>

                        {{-- Cliente --}}
                        <td style="padding:13px 12px;">
                            <div style="display:flex; align-items:center; gap:9px;">
                                <div style="width:27px; height:27px; border-radius:50%; flex-shrink:0;
                                             background:#241f12; border:1px solid #3a3020;
                                             display:flex; align-items:center; justify-content:center;
                                             font-size:11px; color:#c9a84c; font-weight:700;">
                                    {{ strtoupper(substr($reserva->user->name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="color:#e8e0d0; line-height:1.2;">{{ $reserva->user->name ?? '—' }}</div>
                                    @if($reserva->user?->email)
                                        <div style="font-size:11px; color:#5a4f30; margin-top:1px;">{{ $reserva->user->email }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Cholet --}}
                        <td style="padding:13px 12px; color:#d0c8b8; max-width:130px;
                                    overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"
                            title="{{ $reserva->cholet->nombre ?? '' }}">
                            {{ $reserva->cholet->nombre ?? '—' }}
                        </td>

                        {{-- Fecha --}}
                        <td style="padding:13px 12px; white-space:nowrap;">
                            <div style="color:#c8bfae; font-size:13px;">
                                {{ \Carbon\Carbon::parse($reserva->fecha)->format('d M Y') }}
                            </div>
                            <div style="font-size:11px; color:#5a4f30; margin-top:1px;">
                                {{ \Carbon\Carbon::parse($reserva->fecha)->diffForHumans() }}
                            </div>
                        </td>

                        {{-- Experiencia --}}
                        <td style="padding:13px 12px; color:#a09080; max-width:130px;
                                    overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"
                            title="{{ $reserva->experiencia }}">
                            {{ $reserva->experiencia }}
                        </td>

                        {{-- Personas --}}
                        <td style="padding:13px 12px; text-align:center;">
                            <span style="background:#141210; border:1px solid #2e2b25; border-radius:5px;
                                          padding:3px 10px; font-size:12px; color:#c8bfae;">
                                {{ $reserva->personas }}
                            </span>
                        </td>

                        {{-- Total --}}
                        <td style="padding:13px 12px; white-space:nowrap; font-weight:600; color:#c9a84c;">
                            Bs {{ number_format($reserva->precio_total, 2) }}
                        </td>

                        {{-- Estado --}}
                        <td style="padding:13px 12px;">
                            @php
                                $badge = match($reserva->estado) {
                                    'pendiente'  => ['c'=>'#f59e0b','bg'=>'rgba(245,158,11,.12)','l'=>'Pendiente'],
                                    'confirmada' => ['c'=>'#4ade80','bg'=>'rgba(74,222,128,.12)','l'=>'Confirmada'],
                                    'cancelada'  => ['c'=>'#f87171','bg'=>'rgba(248,113,113,.12)','l'=>'Cancelada'],
                                    'completada' => ['c'=>'#60a5fa','bg'=>'rgba(96,165,250,.12)','l'=>'Completada'],
                                    default      => ['c'=>'#7a7060','bg'=>'rgba(122,112,96,.12)','l'=>ucfirst($reserva->estado)],
                                };
                            @endphp
                            <span style="background:{{ $badge['bg'] }}; color:{{ $badge['c'] }};
                                          border:1px solid {{ $badge['c'] }}40; padding:4px 11px;
                                          border-radius:20px; font-size:11px; font-weight:600;
                                          letter-spacing:.04em; white-space:nowrap;">
                                {{ $badge['l'] }}
                            </span>
                        </td>

                        {{-- Acciones --}}
                        <td style="padding:13px 12px; white-space:nowrap;">
                            @if($reserva->estado === 'pendiente')

                                <form action="{{ route('admin.reservas.confirmar',$reserva->id) }}"
                                      method="POST" style="display:inline; margin-right:5px;">
                                    @csrf @method('PUT')
                                    <button type="submit"
                                            style="background:rgba(74,222,128,.1); border:1px solid rgba(74,222,128,.3);
                                                   border-radius:6px; padding:5px 12px; color:#4ade80;
                                                   font-size:12px; font-weight:600; cursor:pointer;"
                                            onmouseover="this.style.background='rgba(74,222,128,.22)';"
                                            onmouseout="this.style.background='rgba(74,222,128,.1)';">
                                        &#10003; Confirmar
                                    </button>
                                </form>

                                <form action="{{ route('admin.reservas.cancelar',$reserva->id) }}"
                                      method="POST" style="display:inline;">
                                    @csrf @method('PUT')
                                    <button type="submit"
                                            style="background:rgba(248,113,113,.1); border:1px solid rgba(248,113,113,.3);
                                                   border-radius:6px; padding:5px 12px; color:#f87171;
                                                   font-size:12px; font-weight:600; cursor:pointer;"
                                            onmouseover="this.style.background='rgba(248,113,113,.22)';"
                                            onmouseout="this.style.background='rgba(248,113,113,.1)';"
                                            onclick="return confirm('¿Cancelar la reserva {{ $reserva->codigo_reserva }}?')">
                                        &#10005; Cancelar
                                    </button>
                                </form>

                            @elseif($reserva->estado === 'confirmada')

                                <form action="{{ route('admin.reservas.completar',$reserva->id) }}"
                                      method="POST" style="display:inline;">
                                    @csrf @method('PUT')
                                    <button type="submit"
                                            style="background:rgba(96,165,250,.1); border:1px solid rgba(96,165,250,.3);
                                                   border-radius:6px; padding:5px 12px; color:#60a5fa;
                                                   font-size:12px; font-weight:600; cursor:pointer;"
                                            onmouseover="this.style.background='rgba(96,165,250,.22)';"
                                            onmouseout="this.style.background='rgba(96,165,250,.1)';">
                                        &#9654; Finalizar
                                    </button>
                                </form>

                            @else
                                <span style="font-size:12px; color:#2e2b25;">—</span>
                            @endif
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="10" style="padding:56px 24px; text-align:center; color:#5a4f30;">
                            <div style="font-size:30px; margin-bottom:12px; opacity:.35;">&#128467;</div>
                            <p style="margin:0 0 6px; font-size:14px; color:#7a7060;">
                                @if(request('codigo'))
                                    Sin resultados para <span style="color:#c9a84c; font-family:monospace;">{{ request('codigo') }}</span>
                                @elseif(request('estado'))
                                    No hay reservas con estado <strong>{{ request('estado') }}</strong>
                                @else
                                    No existen reservas registradas
                                @endif
                            </p>
                            @if(request()->hasAny(['codigo','estado']))
                                <a href="{{ route('admin.reservas.index') }}"
                                   style="font-size:12px; color:#c9a84c; text-decoration:none; margin-top:4px; display:inline-block;">
                                    &#8592; Ver todas las reservas
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

        {{-- ── PAGINACIÓN (si usas ->paginate()) ── --}}
        @if($reservas instanceof \Illuminate\Pagination\LengthAwarePaginator && $reservas->hasPages())
            <div style="display:flex; align-items:center; justify-content:space-between;
                        padding:14px 24px; border-top:1px solid #23211d; font-size:12px; color:#5a4f30;">
                <span>
                    Mostrando {{ $reservas->firstItem() }}–{{ $reservas->lastItem() }}
                    de {{ $reservas->total() }}
                </span>
                <div style="display:flex; gap:6px;">
                    @if($reservas->onFirstPage())
                        <span style="padding:5px 11px; border:1px solid #2e2b25; border-radius:6px; color:#3a3530;">&#8592;</span>
                    @else
                        <a href="{{ $reservas->previousPageUrl() }}"
                           style="padding:5px 11px; border:1px solid #2e2b25; border-radius:6px; color:#7a7060; text-decoration:none;"
                           onmouseover="this.style.borderColor='#c9a84c';this.style.color='#c9a84c';"
                           onmouseout="this.style.borderColor='#2e2b25';this.style.color='#7a7060';">&#8592;</a>
                    @endif

                    @foreach($reservas->getUrlRange(max(1,$reservas->currentPage()-2), min($reservas->lastPage(),$reservas->currentPage()+2)) as $page => $url)
                        <a href="{{ $url }}"
                           style="padding:5px 11px; border-radius:6px; text-decoration:none; font-weight:600;
                                  {{ $page == $reservas->currentPage()
                                        ? 'background:#c9a84c; color:#0f0e0c; border:1px solid #c9a84c;'
                                        : 'border:1px solid #2e2b25; color:#7a7060;' }}"
                           @if($page != $reservas->currentPage())
                               onmouseover="this.style.borderColor='#c9a84c';this.style.color='#c9a84c';"
                               onmouseout="this.style.borderColor='#2e2b25';this.style.color='#7a7060';"
                           @endif>
                            {{ $page }}
                        </a>
                    @endforeach

                    @if($reservas->hasMorePages())
                        <a href="{{ $reservas->nextPageUrl() }}"
                           style="padding:5px 11px; border:1px solid #2e2b25; border-radius:6px; color:#7a7060; text-decoration:none;"
                           onmouseover="this.style.borderColor='#c9a84c';this.style.color='#c9a84c';"
                           onmouseout="this.style.borderColor='#2e2b25';this.style.color='#7a7060';">&#8594;</a>
                    @else
                        <span style="padding:5px 11px; border:1px solid #2e2b25; border-radius:6px; color:#3a3530;">&#8594;</span>
                    </div>
                @endif
            </div>
        @endif

    </div>

</div>

<script>
(function(){
    // Enter en buscador → submit
    document.getElementById('searchInput')
        .addEventListener('keydown', e => { if(e.key==='Enter'){ e.preventDefault(); document.getElementById('searchForm').submit(); } });
})();
</script>

@endsection