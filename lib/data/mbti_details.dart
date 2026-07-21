class MbtiDetail {
  final String type;
  final String name;
  final String description;
  final List<String> strengths;
  final List<String> weaknesses;

  MbtiDetail({
    required this.type,
    required this.name,
    required this.description,
    required this.strengths,
    required this.weaknesses,
  });
}

final Map<String, MbtiDetail> mbtiDetails = {
  "ISTJ": MbtiDetail(
    type: "ISTJ",
    name: "El Inspector",
    description: "Los ISTJ son personas reservadas, prácticas y silenciosas. Disfrutan del orden y la organización en todas las áreas de sus vidas, incluidos el hogar, el trabajo, la familia y los proyectos. Valoran la lealtad en sí mismos y en los demás, y ponen énfasis en las tradiciones. Son responsables por naturaleza y se aseguran de que las tareas se completen con precisión y a tiempo.",
    strengths: ["Responsables", "Organizados", "Leales", "Detallistas"],
    weaknesses: ["Rígidos", "Poco empáticos", "Resistentes al cambio"],
  ),
  "ISFJ": MbtiDetail(
    type: "ISFJ",
    name: "El Protector",
    description: "Convencionales y con los pies en la tierra, los ISFJ disfrutan de la continuidad y la tradición. Tienen un fuerte sentido de la responsabilidad y el deber. Son personas cálidas y protectoras que valoran la armonía. Se esfuerzan por crear un ambiente ordenado y armonioso en casa y en el trabajo. Son meticulosos y persistentes en sus tareas.",
    strengths: ["Serviciales", "Confiables", "Observadores", "Prácticos"],
    weaknesses: ["Demasiado altruistas", "Se toman las cosas personalmente", "Reprimen sus sentimientos"],
  ),
  "INFJ": MbtiDetail(
    type: "INFJ",
    name: "El Consejero",
    description: "Idealistas que tienen un gran sentido de la integridad personal y un impulso para ayudar a otros a alcanzar su potencial. Creativos, dedicados y con una visión profunda. Los INFJ son buscadores de significado y conexión en las ideas, las relaciones y las posesiones materiales. Quieren entender qué motiva a las personas y son perspicaces sobre los demás.",
    strengths: ["Perspicaces", "Inspiradores", "Decididos", "Apasionados"],
    weaknesses: ["Sensibles a la crítica", "Extremadamente reservados", "Propensos al agotamiento"],
  ),
  "INTJ": MbtiDetail(
    type: "INTJ",
    name: "La Mente Maestra",
    description: "Analíticos, lógicos y creativos. Tienen una fuerte necesidad de autonomía y competencia. Son pensadores estratégicos con planes para todo. Los INTJ tienen mentes originales y una gran iniciativa para implementar sus ideas y lograr sus objetivos. Ven rápidamente patrones en los eventos externos y desarrollan perspectivas explicativas de largo alcance.",
    strengths: ["Estratégicos", "Independientes", "Analíticos", "Determinados"],
    weaknesses: ["Arrogantes", "Demasiado críticos", "Desconectados emocionalmente"],
  ),
  "ISTP": MbtiDetail(
    type: "ISTP",
    name: "El Artesano",
    description: "Prácticos y realistas, tienen una afinidad natural por las máquinas y herramientas. Son observadores y valoran la eficiencia y la resolución de problemas de forma inmediata. Son tolerantes y flexibles, observadores silenciosos hasta que aparece un problema, momento en el cual actúan rápidamente para encontrar soluciones funcionales.",
    strengths: ["Optimistas", "Creativos", "Prácticos", "Relajados"],
    weaknesses: ["Tercos", "Insensibles", "Reservados", "Se aburren fácilmente"],
  ),
  "ISFP": MbtiDetail(
    type: "ISFP",
    name: "El Compositor",
    description: "Artísticos, sensibles y amables. Disfrutan del momento presente y de lo que les rodea. Valoran su propio espacio y trabajar a su propio ritmo. Son silenciosos, amigables, sensibles y amables. Disfrutan del aquí y el ahora. Quieren tener su propio espacio y trabajar dentro de su propio marco de tiempo. Son leales y comprometidos con sus valores.",
    strengths: ["Encantadores", "Sensibles", "Imaginativos", "Apasionados"],
    weaknesses: ["Independientes en exceso", "Impredecibles", "Fácilmente estresables"],
  ),
  "INFP": MbtiDetail(
    type: "INFP",
    name: "El Sanador",
    description: "Sensibles, idealistas y leales a sus valores. Tienen curiosidad por las posibilidades del futuro y buscan entender a los demás y ayudarlos. Son idealistas, leales a sus valores y a las personas que son importantes para ellos. Quieren una vida externa que sea congruente con sus valores. Tienen curiosidad por ver posibilidades, catalizando la implementación de ideas.",
    strengths: ["Idealistas", "Buscadores de armonía", "Mente abierta", "Creativos"],
    weaknesses: ["Demasiado idealistas", "Demasiado altruistas", "Se toman las cosas personalmente"],
  ),
  "INTP": MbtiDetail(
    type: "INTP",
    name: "El Arquitecto",
    description: "Lógicos, precisos y reservados. Valoran la inteligencia y el conocimiento. Son teóricos y abstractos, más interesados en las ideas que en la interacción social. Buscan desarrollar explicaciones lógicas para todo lo que les interesa. Teóricos y abstractos, interesados más en las ideas que en la interacción social. Silenciosos, contenidos, flexibles y adaptables.",
    strengths: ["Grandes pensadores", "Objetivos", "Imaginativos", "Entusiastas"],
    weaknesses: ["Desconectados", "Insensibles", "Dudosos", "Condescendientes"],
  ),
  "ESTP": MbtiDetail(
    type: "ESTP",
    name: "El Promotor",
    description: "Enérgicos y orientados a la acción. Disfrutan de los resultados inmediatos y de resolver problemas de forma pragmática. Son sociables y observadores. Flexibles y tolerantes, adoptan un enfoque pragmático centrado en resultados inmediatos. Las teorías y explicaciones conceptuales les aburren: quieren actuar enérgicamente para resolver el problema.",
    strengths: ["Audaces", "Directos", "Sociables", "Perspicaces"],
    weaknesses: ["Insensibles", "Impacientes", "Arriesgados", "Sin visión a largo plazo"],
  ),
  "ESFP": MbtiDetail(
    type: "ESFP",
    name: "El Actor",
    description: "Amantes de la diversión, sociables y entusiastas. Les gusta trabajar con otros para hacer que las cosas sucedan. Tienen un fuerte sentido común y son realistas. Salientes, amigables y aceptadores. Amantes exuberantes de la vida, la gente y las comodidades materiales. Disfrutan trabajando con otros para hacer que las cosas sucedan.",
    strengths: ["Audaces", "Originales", "Estéticos", "Prácticos"],
    weaknesses: ["Sensibles", "Evitan conflictos", "Se aburren fácilmente", "Poca planificación"],
  ),
  "ENFP": MbtiDetail(
    type: "ENFP",
    name: "El Campeón",
    description: "Entusiastas, creativos e imaginativos. Ven la vida como algo lleno de posibilidades. Son cálidos y están dispuestos a ayudar a cualquiera. Cálidamente entusiastas e imaginativos. Ven la vida como llena de posibilidades. Hacen conexiones entre eventos e información con mucha rapidez, y proceden con confianza basándose en los patrones que ven.",
    strengths: ["Curiosos", "Observadores", "Enérgicos", "Populares"],
    weaknesses: ["Dificultad para enfocarse", "Pensamiento excesivo", "Altamente emocionales"],
  ),
  "ENTP": MbtiDetail(
    type: "ENTP",
    name: "El Inventor",
    description: "Rápidos, ingeniosos y estimulantes. Son muy buenos resolviendo problemas nuevos y desafiantes. Valoran la competencia y el pensamiento lógico. Rápidos, ingeniosos, alertas y francos. Recurren a recursos para resolver problemas nuevos y desafiantes. Adeptos a generar posibilidades conceptuales y luego analizarlas estratégicamente.",
    strengths: ["Conocedores", "Pensadores rápidos", "Originales", "Carismáticos"],
    weaknesses: ["Argumentativos", "Insensibles", "Intolerantes", "Dificultad para enfocarse"],
  ),
  "ESTJ": MbtiDetail(
    type: "ESTJ",
    name: "El Supervisor",
    description: "Prácticos, realistas y orientados a los hechos. Tienen una habilidad natural para organizar proyectos y personas para que las cosas se hagan. Prácticos, realistas, directos. Decisivos, se mueven rápidamente para implementar decisiones. Organizan proyectos y personas para que las cosas se hagan, se centran en obtener resultados de la manera más eficiente posible.",
    strengths: ["Dedicados", "Voluntad fuerte", "Directos", "Organizadores"],
    weaknesses: ["Inflexibles", "Incómodos con situaciones nuevas", "Demasiado críticos"],
  ),
  "ESFJ": MbtiDetail(
    type: "ESFJ",
    name: "El Proveedor",
    description: "Cooperativos, sociables y de buen corazón. Buscan la armonía en su entorno y trabajan con determinación para lograrla. Les gusta trabajar con otros. De buen corazón, concienzudos y cooperativos. Quieren armonía en su entorno, trabajan con determinación para establecerla. Les gusta trabajar con otros para completar tareas a tiempo y con precisión.",
    strengths: ["Sentido del deber", "Leales", "Sensibles", "Buenos conectores"],
    weaknesses: ["Preocupados por su estatus", "Inflexibles", "Vulnerables a la crítica"],
  ),
  "ENFJ": MbtiDetail(
    type: "ENFJ",
    name: "El Profesor",
    description: "Cálidos, empáticos y responsables. Son muy sensibles a las necesidades y sentimientos de los demás. Encuentran potencial en todos. Cálidos, empáticos, responsables y altamente sintonizados con las emociones, necesidades y motivaciones de los demás. Encuentran potencial en todos, quieren ayudar a otros a cumplir su potencial.",
    strengths: ["Tolerantes", "Confiables", "Carismáticos", "Altruistas"],
    weaknesses: ["Demasiado idealistas", "Demasiado desinteresados", "Demasiado sensibles"],
  ),
  "ENTJ": MbtiDetail(
    type: "ENTJ",
    name: "El Mariscal de Campo",
    description: "Francos, decididos y líderes naturales. Identifican rápidamente procedimientos ineficientes y desarrollan sistemas para resolver problemas organizativos. Francos, decisivos, asumen el liderazgo fácilmente. Ven rápidamente procedimientos y políticas ilógicas e ineficientes, desarrollan e implementan sistemas integrales para resolver problemas organizativos.",
    strengths: ["Eficientes", "Enérgicos", "Confiados", "Voluntad fuerte"],
    weaknesses: ["Tercos", "Intolerantes", "Impacientes", "Arrogantes"],
  ),
};
